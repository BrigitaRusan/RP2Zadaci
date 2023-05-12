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
        // proslijedi informacije na prikaz logina
        $naslov = "Quack";
        $los_login = false;
        $losa_registracija = false;
        $registracijski_status = false;

        require_once __DIR__."/../view/login_index.php";
    }

    public function login()
    {
        $naslov = "Quack login";
        $_SESSION['glavni naslov'] = "Quack!";
        $los_login = false;
        $losa_registracija = false;
        $registracijski_status = false;

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
                $service = new Service();
                //$service->osvjeziQuackove();
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
