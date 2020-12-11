<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="../css/colors.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@300&family=Patrick+Hand&display=swap" rel="stylesheet">    </head>
    <body>
        <div class="logo">
            <img src="../media/Logotripcuenta.png">
        </div>

        <form action="home.php" method="POST">
            <h1>INICIAR SESIÓN</h1>
            <label for="email"><b>Correo electrónico</b></label><br>
            <input type="email" placeholder="Introduce email" name="email"><br><br>
            <label for="password"><b>Contraseña</b></label><br>
            <input type="password" placeholder="Introduce contraseña" name="password"><br><br>
            <button type="submit">Entrar</button>                
        </form>
    </body>
</html>