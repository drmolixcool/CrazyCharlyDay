<?php

namespace App\action;

use App\Auth;
use App\ConnectionFactory;

class DisplayCompte extends Action
{

    public function execute(): string
    {
        $html ="";
        if ($this->httpMethod = "GET"){
            $act = new SigninAction();
            $html = $act->execute();

        } elseif ($this->httpMethod = "POST") {
            $bd = ConnectionFactory::getConnection();
            $query = "SELECT * FROM Client";

            $req = $bd->prepare($query);



        }


        return $html;
    }


}
