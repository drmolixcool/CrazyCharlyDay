<?php

namespace App\action;
use App\factory\ConnectionFactory;

class DisplayCatalogue extends Action
{

    public function execute(): string
    {
        $html = "";

        if (($db = ConnectionFactory::getConnection()) != null) {
            $query = "SELECT id, nom, prix, description FROM produit";

            $req = $db->prepare($query);
            $req->execute();

            while ($data = $req->fetch()) {
                $html = $html . "<img class='img-serie' src='" . "Images/" . $data["id"] . "' width='400' height='400'>" .
                    $data["nom"] . $data["prix"] . $data["description"];
            }

        }
        return $html;
    }
}