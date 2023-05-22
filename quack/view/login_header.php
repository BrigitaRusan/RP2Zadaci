<!DOCTYPE html>
<html>
<head>
	<meta charset='utf8'>
<style>
* {box-sizing: border-box;}

body { 
  margin: 20px 100px 100px;
  font-family: Arial, Helvetica, sans-serif;
}

.header {
  overflow: hidden;
  background-color: #79828D;
  padding: 20px 10px;
}

.header a {
  float: left;
  color: white;
  text-align: center;
  padding: 12px;
  text-decoration: none;
  font-size: 18px; 
  line-height: 25px;
  border-radius: 4px;
}

.header a.logo {
  font-size: 25px;
  font-weight: bold;
  font: garamond pro semibold italic;
}

.header a:hover {
  background-color: #ddd;
  color: black;
}

.header a.active {
  background-color: #fda4ba;
  color: white;
}

.header-right {
  float: right;
}
.submit{
  background-color: #fda4ba;
}

@media screen and (max-width: 500px) {
  .header a {
    float: none;
    display: block;
    text-align: left;
  }
  
  .header-right {
    float: none;
  }
}
td.center, p.center, h1.center, h2.center{
  text-align: center;
}
div.margin-auto, h1.margin-auto, h2.margin-auto, form.margin-auto{
  margin-left: auto;
  margin-right: auto;
  width: 50%;
  text-align: center;
}
</style>
</head>
<body>

<div class="header">
  <a href="#default" class="logo">Quack!</a>
  <div class="header-right">
  <a class="<?php if ($_SESSION['log']=="login") {echo "active"; } else {echo "noactive";}?>" 
	href="quack.php?rt=login">Log in</a>
  <a class="<?php if ($_SESSION['log']=="signup") {echo "active"; } else {echo "noactive";}?>" 
	href="quack.php?rt=signup">Sign up</a>
  </div>
</div>

