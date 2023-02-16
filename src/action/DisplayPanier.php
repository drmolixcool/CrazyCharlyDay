<?php

namespace App\action;

use App\factory\ConnectionFactory;

class DisplayPanier extends Action
{

    public function execute(): string
    {
        $html='';
        $_SESSION['idUser'] = 1;
        $html .= <<<HTML
            <div id="panier">
            <h1>Votre panier</h1>
            HTML;
        if (($db = ConnectionFactory::getConnection()) != null && isset($_SESSION['idUser'])) {
            $query = "SELECT composition.idProduit,produit.nom,produit.img,produit.prix,produit.poids,produit.lieu,produit.distance from composition inner join produit on produit.id = composition.idProduit inner join panier on panier.id=composition.idPanier where panier.idClient = ?;";
            $req = $db->prepare($query);
            $req->execute([$_SESSION['idUser']]);

            $html .= "<ul>";

            while ($res = $req->fetch()){
                $html .= <<<HTML
                <li>
                <a href="index.php?action=produit&id={$res['idProduit']}">
                <p>{$res['nom']}</p>
                <img src="{$res['img']}" alt="{$res['nom']}">
                <p>{$res['prix']}€</p>
                <p>{$res['poids']}g</p></a>
                </li>
                HTML;
            }

            $query = "SELECT sum(produit.poids*produit.distance) as carbone, sum(produit.prix) as prixTotal from composition inner join produit on produit.id = composition.idProduit inner join panier on panier.id=composition.idPanier where panier.idClient = ?";
            $req = $db->prepare($query);
            $req->execute([$_SESSION['idUser']]);
            $res = $req->fetch();
            if($res['carbone'] == null) $res['carbone'] = 0;
            if($res['prixTotal'] == null) $res['prixTotal'] = 0;
            $html .= <<<HTML
            </ul>
            <label for="carbone">Émission de CO2 : {$res['carbone']} g</label><br>
            <label for="prix">Prix total HT du panier: {$res['prixTotal']} €</label></div>
HTML;
        }
        return $html;
    }
}