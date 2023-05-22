<?php 

require_once __DIR__."/app/database/db.class.php";
require_once __DIR__."/model/user.class.php";

session_start();


// Ova skripta analizira $_GET['niz'] i u bazi postavlja has_registered=1 za onog korisnika koji ima taj niz.
// Jako je mala šansa da dvojica imaju isti.

if( !isset( $_GET['niz'] ) || !preg_match( '/^[a-z]{20}$/', $_GET['niz'] ) )
	exit( 'Nešto ne valja s nizom.' );

// Nađi korisnika s tim nizom u bazi
$db = DB::getConnection();

try
{
	$st = $db->prepare( 'SELECT * FROM dz2_users WHERE registration_sequence=:registration_sequence' );
	$st->execute( array( 'registration_sequence' => $_GET['niz'] ) );
}
catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

$row = $st->fetch();

if( $st->rowCount() !== 1 )
	exit( 'Taj registracijski niz ima ' . $st->rowCount() . 'korisnika, a treba biti točno 1 takav.' );
else
{
	// Sad znamo da je točno jedan takav. Postavi mu has_registered na 1.
	try
	{
		$st = $db->prepare( 'UPDATE dz2_users SET has_registered=1 WHERE registration_sequence=:registration_sequence' );
		$st->execute( array( 'registration_sequence' => $_GET['niz'] ) );
	}
	catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

}
 header( 'location:quack.php?rt=login' );
?>