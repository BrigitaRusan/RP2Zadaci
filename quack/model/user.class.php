<?php

require_once __DIR__."/../app/database/db.class.php";

class User
{
	protected $username, $password, $email, $r_s, $has_registered;

	function __construct( $username, $password )
	{
		$this->username = $username;
		$this->password = $password;
		$this->email = "";
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }

	// pokušaj logirati korisnika
	public function check_login()
	{
		// nađi korisnika u bazi
		try{
			$db = DB::getConnection();
			$upit = $db->prepare("SELECT * FROM dz2_users WHERE username = :username");
			$upit->execute(["username" => $this->username]);
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		// provjeri login i popuni podatke o korisniku
		if($upit->rowCount() === 1){
            $red = $upit->fetch();
            if(password_verify($this->password, $red["password_hash"])){

				$this->username = $red["username"];
				$this->email = $red["email"];
				return true;
			} 
        }
		return false;
	}

	// pokušaj registrirati korisnika
	public function register()
	{
		// provjeri postoji li korisnik s navedenim usernameom u bazi
		try{
			$db = DB::getConnection();
			$upit = $db->prepare("SELECT * FROM dz2_users WHERE username = :username");
			$upit->execute(["username" => $this->username]);
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		// ako ne postoji, registriraj korisnika
		if($upit->rowCount() === 0){
			try{
				$upit = $db->prepare("INSERT INTO dz2_users(username, password_hash, email, registration_sequence, has_registered) VALUES (:username, :password, :email, :r_s, :has_registered)");
				$upit->execute(["username" => $this->username, "password_hash" => $this->password,
								"email" => $this->email, "registration_sequence" => $this->r_s,"has_registered" => $this->has_registered]);
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

			return true;
		}
		return false;
	}
}

?>

