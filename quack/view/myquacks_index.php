<!DOCTYPE html>
<html>
<head>
	<meta charset='utf8'>
<style>
* {box-sizing: border-box;}

#a {
  margin: 150px 100px 100px;
  font-family: Arial, Helvetica, sans-serif;
}
 td {
  border:1px solid gray;
	width: 1000px;
	height: 100px;
	padding: 15px;
	box-shadow: 2px 2px 15px -2px rgb(50, 50, 50);
}
table {
  border-spacing: 30px;
}
input{
	margin: 55px;
	border:1px solid gray;
	width: 600px;
	height: 80px;
	padding: 10px;
	box-shadow: 2px 2px 15px -2px rgb(50, 50, 50);

}
button{
	margin-right: 30px;
	background-color: #a5e5ff;
}
</style>
</head>
<body>

<div id ="a">
<form action = "quack.php?rt=navigacija/myquacks" method = "post" >
	<input type="text" name="noviquack" placeholder = "Type new quack.." maxlength="140">
	<input type="hidden" name="submit_time" value="<?php date_default_timezone_set('Europe/Zagreb'); echo date('Y-m-d H:i:s'); ?>">
	<button type="submit" name ="submit">Post!</button>
</form>

<table>
	<?php

foreach ($myQuacks as $list)
{
			echo '<tr>' .
			     '<td>' . '<span style="color:gray">' . $list['date'] ."<br>". '<span style="color:black">'. $list['quack'] . '</td>' .
			     '</tr>';
		}

	?>
</table>
</div>
