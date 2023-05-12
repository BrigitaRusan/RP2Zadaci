<?php
    
session_start();

class logoutController
{
    public function index()
    {
        session_unset();
        session_destroy();
        header("Location: quack.php?rt=login");
    }
}

?>