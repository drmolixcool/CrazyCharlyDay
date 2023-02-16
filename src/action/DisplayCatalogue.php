<?php

namespace App\action;

use Application\action\Action;

class DisplayCatalogue extends Action
{

    public function execute(): string
    {
        // TODO: Implement execute() method.
    }


    public function display() : String
    {
        $html = "";

        if (($db = ConnectionFactory::makeConnection()) != null) {
            $query = "SELECT nom, prix, description FROM produit";

            $req = $db->prepare($query);
            $req->execute();

            while ($data = $req->fetch()) {
                $html = $html . $data["nom"] . $data["prix"] . $data["description"];
            }

        }

        return $html;

    }
}