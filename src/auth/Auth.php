<?php

namespace App\auth;

use App\factory\ConnectionFactory;

class Auth
{

    public static function authenticate(string $email, string $password): ?User {
        $pdo = ConnectionFactory::getConnection();
        if (!filter_var($email, FILTER_SANITIZE_EMAIL)) return null;
        $getPass = "select passwd, role , active from User where email = :email";
        $req = $pdo->prepare($getPass);
        $req->bindParam(':email', $email);
        $req->execute();

        while ($data = $req->fetch()) {
            $bdHash = $data['passwd'];
            $role = $data['role'];
            $actif = $data['active'];
            if (password_verify($password, $bdHash) && $actif == 1){
                $User = new User($email, $bdHash, $role);
                $User->setId();
                $_SESSION['User'] = serialize($User);
                return $User;
            }
        }
        return null;
    }

    public static function register(string $email, string $password): bool {
        $bd = ConnectionFactory::getConnection();
        $query = "select id from User where email = :email";
        $get = $bd->prepare($query);

        if (filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $get->bindParam(':email', $email);
            $get->execute();
            if (!$get->fetch()) {

                $newPass = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
                $insert = "insert into User(email, passwd, idClient) 
                           values(:email, :password, :idClient)";
                $do = $bd->prepare($insert);

                $do->bindParam(':email', $email);
                $do->bindParam(':password', $newPass);
                $do->execute();

                return true;
            }
        }
        return false;
    }

    public static function checkPassword(string $password, int $length): bool {
        $goodLength = strlen($password) < $length;
        $digit = preg_match("#\d#", $password);
        $special = preg_match("#\W#", $password);
        $lower = preg_match("#[a-z]#", $password);
        $upper = preg_match("#[A-Z]#", $password);
        if ($goodLength || !$digit || !$special || !$lower || !$upper) return false;
        return true;
    }


}