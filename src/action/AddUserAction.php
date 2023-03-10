<?php

namespace App\action;

use App\factory\ConnectionFactory;
use App\auth\Auth;

class AddUserAction
{

    public function execute(): string
    {
        if($_SERVER['REQUEST_METHOD']==="GET"){

            $html = <<<EOF
                <form id="form" method="post" action="?action=inscription" >
                    <label for="form_email">Email</label>
                    <input type="email" id="form_email" name="email" placeholder="<email>" > <br>
                    <br><label for="form_mdp">Mot de passe</label>
                    <input type="password" id="form_mdp" name="password" placeholder="<mot de passe>" > <br>
                    <br><label for="form_confirm">Confirmation du mot de passe</label>
                    <input type="password" id="form_confirm" name="confirm" placeholder="<mot de passe>" > <br>
                    
                    <br><label>Nom</label>
                    <input type="text" id="form_nom" name="nom" placeholder="nom" > <br>
                    <br><label>Prenom</label>
                    <input type="text" id="form_prenom" name="prenom" placeholder="prenom" > <br>
                    <br><label>Adresse</label>
                    <input type="text" id="form_adresse" name="adresse" placeholder="adresse" > <br>
                    <br><label>Telephone</label>
                    <input type="text" id="form_tel" name="telephone" placeholder="telephone" > <br>
                    
                    <br><button type="submit" >S'inscrire</button>
                    <a href='?action=compte' >Retour</a>
            </form>
            
            EOF;
        }elseif ($_SERVER['REQUEST_METHOD']==="POST"){

            if($_POST['password']===$_POST['confirm']){

                $res = Auth::register(filter_var($_POST['email'],FILTER_SANITIZE_EMAIL),$_POST['password'],$_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['telephone'] );


                $html = "<div >
                         <p >Inscription réussie</p>
                         <a href='index.php'>Accueil</a>
                         </div>";

            }
        }
        return $html;
    }

}