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
            $quere = "SELECT nom, prix, description FROM produit";


        }

    }
}