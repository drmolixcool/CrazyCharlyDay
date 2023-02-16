<?php

namespace App\action;

use App\ConnectionFactory;
use App\Auth;

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
                    <br><button type="submit" >S'inscrire</button>
                    <a href='?action=compte' >Retour</a>
            </form>
            
            EOF;
        }elseif ($_SERVER['REQUEST_METHOD']==="POST"){

            if($_POST['password']===$_POST['confirm']){

                $res = Auth::register(filter_var($_POST['email'],FILTER_SANITIZE_EMAIL),$_POST['password']);
                if($res===true){

                    $mail = filter_var($_POST['email']);

                    $db = ConnectionFactory::makeConnection();
                    $query = "SELECT activation_token FROM user WHERE email=:mail";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam("mail", $mail);
                    $stmt->execute();
                    $data = $stmt->fetch();

                    $token = $data['activation_token'];

                    $html = <<<EOF
                    <script>document.location.href="?action=activate&token=$token"</script>
                    EOF;

                }else{
                    $html = "<div >
                            <p>Votre inscription a échoué, veuillez réessayer</p>
                            <a href='index.php' >Retour</a>
                            </div>";
                }
            }else{
                $html = "<div >
                         <p >Vos deux mots de passe ne correspondent pas, veuillez réessayer</p>
                         <a href='index.php'>Retour</a>
                         </div>";
            }
        }
        return $html;
    }

}