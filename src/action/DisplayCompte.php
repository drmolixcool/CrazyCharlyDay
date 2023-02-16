<?php

namespace App\action;

use App\Auth;

class DisplayCompte extends Action
{

    public function execute(): string
    {
        $html ="";
        if ($this->httpMethod = "GET"){
            $act = new SigninAction();
            $html = $act->execute();
        }


        return $html;
    }


}
