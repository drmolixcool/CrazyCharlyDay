<?php

namespace App\action;
use App\factory\ConnectionFactory;

class DisplayCatalogue extends Action
{

    public function execute(): string
    {
        $html = " 
 <div class='catalogue-rechercher'>
 <form action='?action=catalogue' method='post' id='searchbar'>
                    <input type='text' name='search' placeholder='Rechercher' id='searchbar-input'>
                    <input type='submit' value='Rechercher'>
                  </form>
 
</div>
                
                <div id='filter'>
                <form method='POST' action='?action=catalogue'>
                <label for='ville'>Filtrer par ville</label>
                <br> <select name='ville'>
                    <option><button value ='Chauny'>Chauny</button></option>
                    <option><button value ='Les Pennes-Mirabeau'>Les Pennes-Mirabeau</button></option>
                    <option><button value='Leyr'>Leyr</button></option>
                    <option><button value='Lucey'>Lucey</button></option>
                    <option><button value='Nancy'>Nancy</button></option>
                    <option><button value='Pont à Mousson'>Pont à Mousson</button></option>
                    <option><button value='Santeny'>Santeny</button></option>
                    <option><button value='Sarralbe'>Sarralbe</button></option>
                    <option><button value='Villeurbanne'>Villeurbanne</a></option>
                    <option><button value='Wiwersheim'>Wiwersheim</button></option>
                </select>
             
                <br><button type='submit'>Filtrer par ville</button>
                </form>
                
                
             
                <form method='POST' action='?action=catalogue'>
                <label for='categorie'>Filtrer par catégorie</label>
                <br><select name='categorie'>
                    <option><button value ='Épicerie'>Épicerie</button></option>
                    <option><button value ='Boissons'>Boissons</button></option>
                    <option><button value='Droguerie'>Droguerie</button></option>
                    <option><button value='Cosmétiques'>Cosmétiques</button></option>
                    <option><button value='Produits frais'>Produits frais</button></option>
                </select>
                <br><button type='submit'>Filtrer par catégorie</button>
                </form>
                </div>
                
";



        if (($db = ConnectionFactory::getConnection()) != null) {
            $_SESSION['categorie'] = $_POST['categorie'];

            if (isset($_GET['page']) && $_GET['page'] != 1) $page = ($_GET['page']-1)*5 ?? 0;
            else $page = 0;
            if (isset($_POST['search'])) $_SESSION['search'] = $_POST['search'];
            if (isset($_POST['ville'])) $_SESSION['ville'] = $_POST['ville'];
            if (isset($_POST['categorie'])) $_SESSION['categorie'] = $_POST['categorie'];

            $ville = $_SESSION['ville'] ?? '';
            $categorie = $_SESSION['categorie'];

            if (isset($_SESSION['search'])) {
                $recherche = $_SESSION['search'];
                $nbElem = $db->prepare("select count(*) from produit where nom LIKE '%$recherche%'");
                $nbElem->execute();
                $nbElem = $nbElem->fetchColumn();

                if ($nbElem > 0) {
                    $query = "SELECT produit.id,produit.nom,prix,lieu,img FROM produit inner join categorie on produit.categorie = categorie.id WHERE produit.nom LIKE '%$recherche%' and lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%' limit $page,5";
                    $queryCount = "SELECT count(*) FROM produit inner join categorie on produit.categorie = categorie.id WHERE produit.nom LIKE '%$recherche%' and lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%'";
                    $req = $db->prepare($queryCount);
                    $req->execute();
                    $nbPage = ceil($req->fetchColumn()/5);

                } else {
                    $query = "SELECT produit.id,produit.nom,prix,lieu,img FROM produit inner join categorie on produit.categorie = categorie.id where lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%' limit $page,5";
                    $queryCount = "SELECT count(*) FROM produit inner join categorie on produit.categorie = categorie.id where lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%'";
                    $req = $db->prepare($queryCount);
                    $req->execute();
                    $nbPage = ceil($req->fetchColumn()/5);
                }
            } else {
                $query = "SELECT produit.id,produit.nom,prix,lieu,img FROM produit inner join categorie on produit.categorie = categorie.id where lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%' limit $page,5";
                $queryCount = "SELECT count(*) FROM produit inner join categorie on produit.categorie = categorie.id where lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%'";
                $req = $db->prepare($queryCount);
                $req->execute();
                $nbPage = ceil($req->fetchColumn()/5);
            }


            $req = $db->prepare($query);
            $req->execute();

            $html .= <<<END
            <ul class="list-cat" id="catalogue">
END;

            while ($data = $req->fetch()) {
                $html .= <<<END
                    <li class="cat">
                        <a href="index.php?action=produit&id={$data['id']}">
                         <img src="{$data['img']}" alt="{$data['nom']}">
                        <h3>{$data['nom']}</h3>
                           
                            <p>{$data['prix']}€</p>
                            <p>{$data['lieu']}</p>
                        </a>
                    </li>  
END;
            }
            $html .= <<<END
            </ul>
            <div id="pagination">
END;
            for ($i = 1; $i <= $nbPage; $i++) {
                $html .= "<a id='page' href='?action=catalogue&page=$i'>$i</a>";
            }
            $html .= "</div>";
        }

        return $html;
    }
}
