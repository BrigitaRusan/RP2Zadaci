<?php require_once __DIR__."/_header.php";?>
<style>
* {box-sizing: border-box;}

body {
  margin: 20px 100px 100px;
  font-family: Arial, Helvetica, sans-serif;
}

.headern {
  overflow: hidden;
  background-color: #FCFFFF;
  padding: 20px 10px;
}

.headern a {
   float: none; /*left */
  color: black;
  text-align: center;
  padding: 20px;
  text-decoration: none;
  font-size: 18px;
  line-height: 25px;
  border-radius: 4px;
}

.headern a.logo {
  font-size: 25px;
  font-weight: bold;
}

.headern a:hover {
  background-color: #A5E5FF;
  color: black;
}

/* .headern a.active {
  background-color: #fda4ba;
  color: white;
} */
.headern a.active  {
    color: #00abf0!important;
    border-bottom: 1px solid #00abf0!important;
    background-image: none !important;
}

.headern {
  float: left;
}

</style>
  <div class="headern">
	<a href="#default" class="logo"><img src="quack.jpg" style="width:90px; height:90px;"></a>
	<a class="<?php if ($_SESSION['aktivan']=="myquacks") {echo "active"; } else {echo "noactive";}?>"
	href="quack.php?rt=navigacija/myquacks"> My quacks</a>
	<a class="<?php if ($_SESSION['aktivan']=="following") {echo "active"; } else {echo "noactive";}?>"
	href="quack.php?rt=navigacija/following"> Following</a>
	<a class="<?php if ($_SESSION['aktivan']=="followers") {echo "active"; } else {echo "noactive";}?>"
	href="quack.php?rt=navigacija/followers"> Followers </a>
	<a class="<?php if ($_SESSION['aktivan']=="quacks") {echo "active"; } else {echo "noactive";}?>"
	href="quack.php?rt=navigacija/quacks"> quacks</a>
	<a class="<?php if ($_SESSION['aktivan']=="search") {echo "active"; } else {echo "noactive";}?>"
	href="quack.php?rt=navigacija/search"> #search</a>
  </div>
<?php require_once __DIR__."/_footer.php";?>
