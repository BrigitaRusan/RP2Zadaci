<?php
require_once __DIR__."/../model/service.class.php";

session_start();

class navigacijaController
{
	public function index()
	{
		$user = $_SESSION['user'];

		if ( isset( $_POST['noviquack']) && isset ($_POST['submit'])  )
		{
			$quack = htmlentities($_POST['noviquack']);
			unset($_POST['noviquack']);
			$date = htmlentities($_POST['submit_time']);

			$ls = new Service();
			$ls->spremiQuack($user, $quack, $date);
		}

		$ls = new Service();
		$myQuacks = $ls->quackoviPoDatumu($user);

		$_SESSION['aktivan'] = "myquacks";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/myquacks_index.php';
	}


	public function myquacks()
	{
		$user = $_SESSION['user'];

		if ( isset( $_POST['noviquack']) && isset ($_POST['submit']) )
		{
			$quack = htmlentities($_POST['noviquack']);
			unset($_POST['noviquack']);
			$date = htmlentities($_POST['submit_time']);

			$ls = new Service();
			$ls->spremiQuack($user, $quack, $date);
		}

		$ls = new Service();
		$myQuacks = $ls->quackoviPoDatumu($user);

		$_SESSION['aktivan'] = "myquacks";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/myquacks_index.php';
	}


	public function following()
	{
		$user = $_SESSION['user'];

		if ( isset( $_POST['brisif']) )
		{
			$follow = htmlentities($_POST['brisif']);
			unset($_POST['brisif']);

			$ls = new Service();
		 	$ls->obrisiFollowing($user, $follow);
		}
		if ( isset( $_POST['prati']) )
		{
			$follow = htmlentities($_POST['ime']);
			unset($_POST['ime']);

			$ls = new Service();
			$ls->newFollowing( $user, $follow );
		}

		$ls = new Service();
		$listFollowing = $ls->allFollowing( $user );
		$ls = new Service();
		$followingQuacks = $ls->quackoviPoDatumuFollowing( $listFollowing );

		$_SESSION['aktivan'] = "following";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/following_index.php';
	}


	public function followers()
	{
		$user = $_SESSION['user'];

		if ( isset( $_POST['brisi']) )
		{
			$follow = htmlentities($_POST['brisi']);
			unset($_POST['brisi']);

			$ls = new Service();
			$ls->obrisiFollower($user, $follow);
		}

		$ls = new Service();
		$followerList = $ls->allFollowers( $user );

		$_SESSION['aktivan'] = "followers";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/followers_index.php';
	}


	public function quacks()
	{
		$user = $_SESSION['user'];

		$ls = new Service();
		$quackList = $ls->quackoviPoUsernameu($user);

		$_SESSION['aktivan'] = "quacks";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/quacks_index.php';
	}


	public function search()
	{
		$user = $_SESSION['user'];

		if ( isset( $_POST['send']) )
		{
			$hashtag = htmlentities($_POST['search']);
			unset($_POST['search']);

			$ls = new Service();
			$pos = stripos($hashtag, "#");
			if ( !($pos !== false) ) { $hashtag = "#".$hashtag; }
			$quackList = $ls->quackoviPoHashtagu($hashtag);
		}

		$_SESSION['aktivan'] = "search";
		require_once __DIR__ . '/../view/navigacija_index.php';
		require_once __DIR__ . '/../view/search_index.php';
	}
};

?>
