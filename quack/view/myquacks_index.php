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
</style>
</head>
<body>
<div id ="a">


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
