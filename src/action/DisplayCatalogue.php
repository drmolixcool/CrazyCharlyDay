<?php

namespace App\action;

use Application\action\Action;

class DisplayCatalogue extends Action
{

    public function execute(): string
    {
        // TODO: Implement execute() method.
    }


    public function displayHTML() : String
    {
        $html = "";



        return $html;
    }

    public function displayCat() : String
    {
        $html = "";

        if (($db = ConnectionFactory::makeConnection()) != null) {
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