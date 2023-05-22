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
p{
	color: #79828d;
	 margin-left: 30px;
}
</style>
</head>
<body>
<div id ="a">

	<form action = "quack.php?rt=navigacija/search" method = "post" >
		<input type="text" name="search" placeholder = "Search..">
		<button type="submit" name ="send">Search!</button>
	</form>
		<?php

		if (isset ($hashtag ) )
		{
			echo '<p> Results for '. $hashtag .': </p>';
		}
	?>

	<table>
		<?php
		if (isset ($quackList ) )
		{
			foreach ($quackList as $list)
			{
				echo '<tr>' .
						 '<td>' . '<span style="color:gray">' .$list['username'].' - ' . $list['date']."<br>". '<span style="color:black">'. $list['quack'] . '</td>' .
						 '</tr>';
			}
		}
		?>
	</table>

</div>
