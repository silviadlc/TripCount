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
				<h1 class="WelcomeText">¡Bienvenido a Trip Cuenta!</h1>
			</div>
			<div class="Errors">
			</div>
			<div class="SubWelcome">
				<h3 class="SubWelcomeText">Trip Cuenta es una página dedicada a la gestión de gastos en viajes organizados.</h3>
			</div>
			<div class="landingButtons">
				<div class="logo">
					<img src="media/Logotripcuenta.png"">
				</div>
				<div class="access">
					<form method="POST" action="login.php">
						<button type="submit" name="login" class="inputButton" accesskey="l"><underline class="accesskey">L</underline>ogin</button>
					</form>
					<form method="POST" action="core/register.php">
						<button type="submit" name="register" class="inputButton" accesskey="r"><underline class="accesskey">R</underline>egister</button>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>