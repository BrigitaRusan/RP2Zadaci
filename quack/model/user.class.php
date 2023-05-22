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
		if( $upit->rowCount() !== 0 )
		{
			// Taj user u bazi već postoji
			return false;
			//exit();
		}

		// Dodaj novog korisnika u bazu. 
		// Prvo mu generiraj random string od 10 znakova za registracijski link.
		$registration_sequence = '';
		for( $i = 0; $i < 20; ++$i )
			$registration_sequence .= chr( rand(0, 25) + ord( 'a' ) ); // Zalijepi slučajno odabrano slovo

		try
		{
			$st = $db->prepare( 'INSERT INTO dz2_users(username, password_hash, email, registration_sequence, has_registered) VALUES ' .
								'(:username, :password, :email, :registration_sequence, "0")' );
			
			$st->execute( array( 'username' => $_POST['username'], 
								'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT ), 
								'email' => $_POST['email'], 
								'registration_sequence'  => $registration_sequence ) );
		}
		catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

		
		// Sad mu još pošalji mail
		$to       = $_POST['email'];
		$subject  = 'Registracijski mail';
		$message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
		$message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/register.php?niz=' . $registration_sequence . "\n";
		$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
					'Reply-To: rp2@studenti.math.hr' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

		$isOK = mail($to, $subject, $message, $headers);

		if( !$isOK )
		{
			exit( 'Greška: ne mogu poslati mail. (Pokrenite na rp2 serveru.)' );
		}

		return true;

	}
}

?>

