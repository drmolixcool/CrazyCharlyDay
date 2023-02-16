<?php

namespace App\action;
use App\factory\ConnectionFactory;

class DisplayCatalogue extends Action
{

    public function execute(): string
    {
        $html = <<<EOF
<form action='?action=catalogue' method='post'>
<div class='search'>
    <input type='text' name='search' placeholder='Rechercher' id='searchbar-input'>
    <input id='button' type='submit' value='Rechercher'>
</div>
                
<div id='filter'>
<label for='ville'>Filtrer par ville</label>
<br>
<select name='ville' id='ville-select' style='text-align: center'>
    <option value=''>--- Ville ---</option>
    <option value='Chauny'>Chauny</option>
    <option value='Les Pennes-Mirabeau'>Les Pennes-Mirabeau</option>
    <option value='Leyr'>Leyr</option>
    <option value='Lucey'>Lucey</option>
    <option value='Nancy'>Nancy</option>
    <option value='Pont à Mousson'>Pont à Mousson</option>
    <option value='Santeny'>Santeny</option>
    <option value='Sarralbe'>Sarralbe</option>
    <option value='Villeurbanne'>Villeurbanne</option>
    <option value='Wiwersheim'>Wiwersheim</option>
</select>

<label for='categorie'>Filtrer par catégorie</label>
<br>
<select name='categorie' id='categorie-select'>
    <option value=''>--- Catégorie ---</option>
    <option value='Épicerie'>Épicerie</option>
    <option value='Boissons'>Boissons</option>
    <option value='Droguerie'>Droguerie</option>
    <option value='Cosmétiques'>Cosmétiques</option>
    <option value='Produits frais'>Produits frais</option>
</select>
<br>
</div>             
</form>
EOF;



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
