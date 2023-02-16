<?php

namespace App\action;
use App\factory\ConnectionFactory;

class DisplayCatalogue extends Action
{

    public function execute(): string
    {
        $html = " <form action='index.php?action=catalogue' method='post' id='searchbar'>
                    <input type='text' name='search' placeholder='Rechercher'>
                    <input type='submit' value='Rechercher'>
                </form>";



        if (($db = ConnectionFactory::getConnection()) != null) {
            $query = "SELECT ceil(count(*)/5) FROM produit";
            $req = $db->prepare($query);
            $req->execute();
            $nbPage = $req->fetchColumn();
            if (isset($_POST['search'])) {
                $recherche = $_POST['search'];
                $recherche = strtolower($recherche);
                $query = "SELECT id,nom,prix,lieu,img FROM produit WHERE nom LIKE '%$recherche%'";
            } else {
                $query = "SELECT id,nom,prix,lieu,img FROM produit";
            }


            $req = $db->prepare($query);
            $req->execute();

            $html .= <<<END
            <ul class="list-cat">
END;

            while ($data = $req->fetch()) {
                $html .= <<<END
                    <li class="cat">
                        <a href="index.php?action=produit&id={$data['id']}">
                        <h3>{$data['nom']}</h3>
                            <img src="{$data['img']}" alt="{$data['nom']}">
                            <p>{$data['prix']}€</p>
                            <p>{$data['lieu']}</p>
                        </a>
                    </li>  
END;
            }
            $html .= <<<END
            </ul>
END;
        }
        return $html;
    }
}