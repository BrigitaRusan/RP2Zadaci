<?php

require_once __DIR__."/../model/user.class.php";
require_once __DIR__ . "/../model/service.class.php";
require_once __DIR__."/../app/database/db.class.php";
session_start();
$_SESSION['log']= "login";

class loginController
{
    public function index()
    {
        $register = false;
        $los_login = false;
        require_once __DIR__."/../view/login_index.php";
    }

    public function login()
    {
        $los_login = false;
        $register = false;

        // provjeri jesu li stigle login informacije
        if(!isset($_POST["username"]) || !isset($_POST["password"])){
            $los_login = true;
            // vrati natrag na login stranicu
            require_once __DIR__."/../view/login_index.php";
        }
        // pokušaj obaviti login
        else{
            $username = htmlentities($_POST["username"]);
            $password = htmlentities($_POST["password"]);

            $user = new User($username, $password);

            if($user->check_login())
            {
                $_SESSION["user"] = $username;
                header("Location: quack.php?rt=navigacija");
            }
            else{
                $los_login = true;
                require_once __DIR__."/../view/login_index.php";
            }
        }
    }

}
?>
