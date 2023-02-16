<?php

namespace App\action;

use App\Auth;

class SigninAction extends Action
{

    public function execute() : string {
        $html = "";
        if($this->httpMethod === "GET") {
            $html = "
                <div class='text-center'>
                <form method='post'>
                    <label>Identifiant : </label><input type='email' name='email' placeholder='toto@gmail.com' > <br> <br>
                    <label>Mot de passe : </label><input type='password' name='password' placeholder='mot de passe' > <br> <br>
                    <button type='submit' >Valider</button>
                </form>
                </div>";
        }
        elseif ($this->httpMethod === "POST") {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $passwd = $_POST['password'];
            $user = Auth::authenticate($email, $passwd);
            if ($user != null) {
                $html = "<script>document.location.href='index.php'</script>";
            } else {
                $html = "<div class='text-center text-red-600'>
                <p>Votre email ou mot de passe est incorrect</p><br>
                <a href='?action=signin' >Retour</a>
                </div>";
            }
        }
        return $html;
    }

}