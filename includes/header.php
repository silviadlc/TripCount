<?php require $_SERVER["DOCUMENT_ROOT"].'/core/functions.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8">
		<title><?php echo getTitleDocument(); ?> Tripcount</title>
		<?php notAllowedToEnterIfNotLogged(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="<?php echo getCSSdependingUrl(); ?>">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@300&family=Patrick+Hand&display=swap" rel="stylesheet"> 
        <script src="/js/script.js" defer></script>
	  	<script src="https://kit.fontawesome.com/74ec47558a.js" crossorigin="anonymous"></script>
	</head>
	<header>
	<div class="header">
		<div><?php if(isset($_SESSION['userLogged'])){
			echo getUsernameById($localUser['idUsername']);
		} ?>
		</div>
		<div>
			<a href="./home.php" class="home">
	  		<i class="fas fa-home" accesskey="h"></i>
			</a>
		</div>
	</header>
	<body>