<?php 

session_start();

class _404Controller
{
	public function index() 
	{
		$title = 'Stranica nije pronađena.';

		require_once __DIR__ . '/../view/404_index.php';
	}
}; 

?>
