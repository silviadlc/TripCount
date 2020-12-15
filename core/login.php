<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="../css/colors.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@300&family=Patrick+Hand&display=swap" rel="stylesheet">    
    </head>
    <?php
        include "connection.php";
        include "errors.php";

        $errors = [];
        $has_error = false;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userEmail']) && isset($_POST['userPass'])) {

            $_user = $_POST['userEmail'];
            $_password = $_POST['userPass'];
            $passwordEncrypt = password_hash($_password, PASSWORD_DEFAULT);

            $query = $conn -> prepare('SELECT * FROM users WHERE email= ? AND password= ?');
            $query ->bindParam(1, $_user);
            $query ->bindParam(2, $_password);
            $query ->execute();
            $result = $query -> fetch();
            
            if($result != false && password_verify($_password, $passwordEncrypt)){
                $_SESSION['user'] = $result['idUsername'];
                errorMessage('success', 'Usuario y password validados'); 
            } else {
                errorMessage('error', 'Usuario y password no coinciden');
            }
            unset($_POST['userEmail'], $_POST['userPass']);
        }
    ?>
    <body>
        <div class="logo">
            <img src="../media/Logotripcuenta.png">
        </div>

        <form action="login.php" method="POST">
            <h1>INICIAR SESIÓN</h1>
            <label for="userEmail"><b>Correo electrónico</b></label><br>
            <input type="email" placeholder="Introduce email" name="userEmail"><br><br>
            <label for="userPass"><b>Contraseña</b></label><br>
            <input type="password" placeholder="Introduce contraseña" name="userPass"><br><br>
            <button type="submit" name="login">Entrar</button>                
        </form>
    </body>
</html>