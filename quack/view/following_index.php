<!DOCTYPE html>
<html>
<head>
	<meta charset='utf8'>
<style>
* {box-sizing: border-box;}

#a {
  margin: 200px 50px 50px;
  font-family: Arial, Helvetica, sans-serif;
}

.a {
    width: 100%;
		width: 100%;
		height: 200px;
}
.b1 {
	  position: relative;
    float: left;
    width: 60%;
		height: 100%;
}
.b2 {
	  position: relative;
    float: left;
    width: 40%;
}
.red {
	 	margin-left: 20px;
    height: 100%;
		width: 100%;
		float: right;
}
.top {
    width: 100%;
    height: 50%;
}
.bottom {
	  margin-top: 20px;
    width: 100%;
    height: 50%;
}

th, td, input{
  border:1px solid gray;
	border-collapse: collapse;
	height: 100px;
	text-align: left;
	padding-left: 20px;
}
table {
  border-spacing: 30px;
}
button{
	background-color: #a5e5ff;
}
td,th, input, #prati{
	box-shadow: 2px 2px 15px -2px rgb(50, 50, 50);
}
p{
	color: #79828d;
	margin-left: 30px;
}
input{
	width: 600px;
	height: 60px;
}
#prati{
	height: 60px;
	width: 60px;
	margin-left: 10px;
}
.desno_tablica {
	border:1px solid gray;
	border-collapse: collapse;
	height: 100px;
	text-align: left;
	width: 500px;
}
.desno_input {
	border:1px solid gray;
	border-collapse: collapse;
	height: 80px;
	text-align: left;
	width: 400px;
}

</style>
</head>
<body>

	<div id ="a" class ="a">
		<div class="b1">
			<div class="red">
				<p>Quacks from following:</p><br>
			<table>
				<?php
				foreach ($followingQuacks as $list)
				{
					echo '<tr>' .
					     '<td>' . '<span style="color:gray">'.'@'.$list['username'].' - ' . $list['date'] ."<br>". '<span style="color:black">'. $list['quack'] . '</td>' .
					     '</tr>';
				}
				?>
				</table>
		</div>
		</div>
		<div class="b2">
			<div class="top">
				<p>New following:</p><br>
			<table>
			<form action="quack.php?rt=navigacija/following" method="post">
				<input class= "desno_input" type="text" placeholder="Type user name.." name="ime"></input>
				<button type="submit" id="prati" name="prati" value= "!">Prati!</button>
			</form>
			</table>
			</div>
			<div class="bottom">
				<p>Following:</p><br>
				<form action="quack.php?rt=navigacija/following" method="post">
					<table class= "desno_tablica">
						<?php
							foreach( $listFollowing as $user )
							{
								echo '<tr class="desno_tablica">' .
										 '<td style="width:800px">' . $user[0] . '</td>' .
										 '<td style="width:200px"><button type="submit" name="brisif" value="'.$user[0].'">x</button></td>'.
										 '</tr>';
							}
						?>
					</table>
				</form>
			</div>

		</div>
</div>
