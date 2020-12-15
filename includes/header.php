<?php session_start()
if (isset($_POST["user"])){
        $_SESSION["user"] = $_POST["user"];

 ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
  	<script src="https://kit.fontawesome.com/74ec47558a.js" crossorigin="anonymous"></script>
</head>
<body>
	<link rel="stylesheet" type="text/css" href="../css/colors.css">
	<link rel="stylesheet" type="text/css" href="../css/header.css">
<div class="header">
	<div><?php echo"$_SESSION['user']"; ?></div>
	<div><a href="./home.php" class="home"><span style="font-size: 40px; color:white;">
  <i class="fas fa-home"></i>
</span></a></div>
</div>