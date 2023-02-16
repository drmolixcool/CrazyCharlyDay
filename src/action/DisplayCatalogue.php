<?php

namespace App\action;

use App\factory\ConnectionFactory;

class DisplayCatalogue extends Action
{

    public function execute(): string
    {
        $_SESSION['idUser'] = 1;
        $html = " <form action='?action=catalogue' method='post' id='searchbar'>
                    <input type='text' name='search' placeholder='Rechercher'>
                    <input type='submit' value='Rechercher'>
                  </form>
                
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
            $query = "SELECT ceil(count(*)/5) FROM produit";
            $req = $db->prepare($query);
            $req->execute();
            $nbPage = $req->fetchColumn();
            $ville = $_POST['ville'] ?? '';
            $categorie = $_POST['categorie'] ?? '';

            if (isset($_POST['search'])) {
                $recherche = $_POST['search'];
                $recherche = strtolower($recherche);
                $nbElem = $db->prepare("select count(*) from produit where nom LIKE '%$recherche%'");
                $nbElem->execute();
                $nbElem = $nbElem->fetchColumn();
                if ($nbElem > 0) {
                    $query = "SELECT produit.id,produit.nom,prix,lieu,img,poids FROM produit inner join categorie on produit.categorie = categorie.id WHERE produit.nom LIKE '%$recherche%' and lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%'";
                } else {
                    $query = "SELECT produit.id,produit.nom,prix,lieu,img,poids FROM produit inner join categorie on produit.categorie = categorie.id where lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%'";
                }
            } else {
                $query = "SELECT produit.id,produit.nom,prix,lieu,img,poids FROM produit inner join categorie on produit.categorie = categorie.id where lieu LIKE '%$ville%' and categorie.nom LIKE '%$categorie%'";
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
    <div class="cat-prod">
        <img src="{$data['img']}" alt="{$data['nom']}">
        <div class="cat-prod-int">
        <h3>{$data['nom']}</h3>
END;
                if ($data['poids'] == 0) {
                    $html .= <<<END
        <p>Prix : {$data['prix']}€/kg</p>
END;
                } else {
                    $html .= <<<END
        <p>Prix : {$data['prix']}€</p>
        <p>Poids : {$data['poids']} grammes</p>
END;
                }
                $html .= <<<END
        <p>Ville du fournisseur : {$data['lieu']}</p>
        </div>
    </div>
    </a>
END;
                if ($_SESSION['idUser'] > 0) {

                    if ($data['poids'] == 0) {
                        $html .= <<<END
                        <form action="" method="post">
                        <input type="hidden" name="article" value="{$data['id']}">
                            <label>Quantité en kg : </label><input type="number" name="qt" value="1" min="1" max="50">
                            <input type="submit" value="Ajouter au panier">
                        </form>
                     
END;
                    } else {
                        $html .= <<<END
                        <form action="" method="post">
                            <input type="hidden" name="article" value="{$data['id']}">
                            <label>Quantité en unité : </label><input type="number" name="qt" value="1" min="1" max="50">
                            <input type="submit" value="Ajouter au panier">
                        </form>
                     
END;
                    }
                }

                $html .= <<<END

                       </li>
END;
            }
            $html .= <<<END
            </ul>
           
END;

            for ($i = 1; $i <= $nbPage; $i++) {
                $html .= "<a href='?action=catalogue&page=$i'>$i</a>";
            }
            if (isset($_POST['article'])) {
                $id = $_POST['article'];
                $qt = $_POST['qt'];
                $id = intval($id);
                $qt = intval($qt);
                $query = "select id from panier where idClient=:id_user";
                $req = $db->prepare($query);
                $req->bindParam(':id_user', $_SESSION['idUser']);
                $req->execute();
                $idPanier = $req->fetchColumn();
                $query = "INSERT INTO composition (idPanier,idProduit,qte) VALUES (:id_panier,:id_produit,:qte)";
                $req = $db->prepare($query);
                $req->bindParam(':id_panier', $idPanier);
                $req->bindParam(':id_produit', $id);
                $req->bindParam(':qte', $qt);
                $req->execute();
                }

            }
        return $html;
    }
}