<!DOCTYPE html>
<html>
<head>
	<meta charset='utf8'>
<style>
* {box-sizing: border-box;}

#a {
  margin: 200px 100px 100px;
  font-family: Arial, Helvetica, sans-serif;
}
table, th, td {
  border:1px solid gray;
	border-collapse: collapse;
	height: 100px;
	text-align: center;
}
button{
	background-color: #a5e5ff;
}
td,th{
	box-shadow: 2px 2px 15px -2px rgb(50, 50, 50);
}
</style>
</head>
<body>
<div id ="a">


<table>
	<?php
		foreach( $followerList as $user )
		{
			echo '<tr>' .
			     '<td style="width:800px">' . $user[0] . '</td>' .
					 '<td style="width:200px"><button>x</button></td>'.
			     '</tr>';
		}
	?>
</table>


</div>
