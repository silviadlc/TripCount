<!DOCTYPE html>
<html>
	<head>
		<?php require $_SERVER["DOCUMENT_ROOT"].'/core/functions.php'; ?>
		<title><?php echo getTitleDocument(); ?></title>
		<meta charset="utf8">
        
        <title><?php echo getTitleDocument(); ?> Tripcount</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="<?php echo getCSSdependingUrl(); ?>">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@300&family=Patrick+Hand&display=swap" rel="stylesheet"> 
        <script src="/js/script.js" defer></script>
	  	<script src="https://kit.fontawesome.com/74ec47558a.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="css/landing.css" type="text/css">
	</head>
	<body>
		<div class="Welcome">
			<div class="WelcomeTDiv">
				<h1 class="WelcomeText">Bienvenido a Trip Cuenta!</h1>
			</div>
			<div class="Errors">
			</div>
			<div class="SubWelcome">
				<h3 class="SubWelcomeText">Trip Cuenta es una pagina dedicada a la gestión de gastos en viajes organizados.</h3>
			</div>
			<div class="landingButtons">
				<form method="POST" action="login.php">
					<input type="submit" name="login" value="Login" class="inputButton">
				</form>
				<img src="media/Logotripcuenta.png" width="100px" height="50px">
				<form method="POST" action="core/register.php">
					<input type="submit" name="register" value="Register" class="inputButton">
				</form>
		</div>
		</div>

		
	</body>
</html>