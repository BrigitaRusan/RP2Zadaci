<?php
crtaj_loginForma();

function crtaj_loginForma( )
{
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf8" />
		<title>Login</title>
        <style>
        #pocetak{border: 1px solid blue; width:500px; height: 150px;}
        </style>
	</head>
	<body>
    <div id="pocetak">

    <h1>Sokoban</h1>
		<form method="post" action="igra.php">
      <label for="ime">Unesi ime igrača: </label>
			<input type="text" id="ime" name="ime" />
			<button type="submit" name="button" value="login">Započni igru!</button><br>
			<select name="razina_igre">
				<option value="1">Level1</option>
				<option value="2">Level2</option>
			</select>
		</form>

    </div>
	</body>
	</html>
<?php
}
?>
