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
        $losa_registracija = false;
        $registracijski_status = false;

        require_once __DIR__."/../view/signup_index.php";
    }

    public function signup()
    {
        $losa_registracija = false;
        $registracijski_status = false;
        $los_login = false;


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
            if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
            {
                //$losa_registracija = true;
            }
            else
            {
                $email= htmlentities($_POST["email"]);
                $user = new User($username, $password);
                $user->email = $email;
            }

            if($user->register())
            {
                $registracijski_status = true; 
                $register = true;         
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