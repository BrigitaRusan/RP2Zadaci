<?php 

session_start();

class indexController
{
	public function index() 
	{
		// Samo preusmjeri na login stranicu.
		header( 'Location: quack.php?rt=login' );
	}
}; 

?>
