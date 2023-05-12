<?php

require_once __DIR__."/../model/user.class.php";
require_once __DIR__ . "/../model/service.class.php";
require_once __DIR__."/../app/database/db.class.php";
session_start();
$_SESSION['log']= "signup";

class signupController
{
    public function index()
    {
        // proslijedi informacije na prikaz logina
        $naslov = "Quack";
        $los_login = false;
        $losa_registracija = false;
        $registracijski_status = false;

        require_once __DIR__."/../view/signup_index.php";
    }
    public function signup()
{
    $naslov = "Quack sign in";
    $los_login = false;
    $losa_registracija = false;
    $registracijski_status = false;

    // provjeri jesu li stigle registracijske informacije
    if(!isset($_POST["username"]) || !isset($_POST["password"])){
        $losa_registracija = true;
        // vrati natrag na login stranicu
        require_once __DIR__."/../view/signup_index.php";
    }
    // pokušaj obaviti registraciju
    else{
        $username = htmlentities($_POST["username"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $email= htmlentities($_POST["email"]);

        $user = new User($username, $password, $email);

        if($user->register())
        {
            $registracijski_status = true;
            require_once __DIR__."/../view/login_index.php";
        }
        else{
            $losa_registracija = true;
            require_once __DIR__."/../view/signup_index.php";
        }
    }
}
}
?>