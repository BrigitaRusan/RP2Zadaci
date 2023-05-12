<?php
require_once __DIR__."/../model/service.class.php";

session_start();

class navigacijaController
{
	public function index()
	{
		$user = $_SESSION['user'];
		$ls = new Service();
		$myQuacks = $ls->quackoviPoDatumu($user);

		$_SESSION['aktivan']= "myquacks";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/myquacks_index.php';
	}
	public function myquacks()
	{
		$user = $_SESSION['user'];
		$ls = new Service();
		$myQuacks = $ls->quackoviPoDatumu($user);

		$_SESSION['aktivan']= "myquacks";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/myquacks_index.php';
	}
	public function following()
	{
		$_SESSION['aktivan']= "following";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/following_index.php';
	}
	public function followers()
	{

		$user = $_SESSION['user'];
		$ls = new Service();
		$followerList = $ls->allFollowers( $user );

		$_SESSION['aktivan']= "followers";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/followers_index.php';
	}
	public function quacks()
	{
		$_SESSION['aktivan']= "quacks";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/quacks_index.php';
	}
	public function search()
	{
		$_SESSION['aktivan']= "search";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/search_index.php';
	}
};

?>
