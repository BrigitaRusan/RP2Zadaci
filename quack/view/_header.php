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
  color: #fda4ba;
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
  color: #ffff;
  font: garamond pro semibold italic;
}

.header-right {
  float: right;
}
.submit{
  background-color: #fda4ba;
}

@media screen and (max-width: 300px) { //500
  .header a {
    float: none;
    display: block;
    text-align: left;
  }

  .header-right {
    float: none;
  }
}

</style>
</head>
<body>

<div class="header">
  <a  class="logo">Quack!</a>
  <div class="header-right">
    <a class="active" href="quack.php?rt=logout"> <?php echo '<span style="color:white">'. '@'. $_SESSION['user'] ; ?> <br><span style="color: #fda4ba"> Log out</a>

  </div>
</div>
