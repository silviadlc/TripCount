<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invitaciones</title>
        <link rel="stylesheet" href="css/invitations.css">
        <link rel="stylesheet" href="css/colors.css">
        <link rel="shortcut icon" href="media/tricount_logo.png">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@300&family=Patrick+Hand&display=swap" rel="stylesheet">    
        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <?php //include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php' ?>

    <body>
        <div class="logo">
            <img src="media/Logotripcuenta.png">
        </div>

        <h1>INVITACIONES</h1>
        <div class="form">
        <p>Aquí podrás introducir el correo electrónicos de los amigos/as con los que quieres compartir ésta experiencia en <? $tripName ?></p>
            <form method="POST">
                <label for="userEmail"><b>Correos electrónicos</b></label><br>
                <div class="emailList">
                    <ol>
                        <li>
                            <input type="email" placeholder="Introduce correo electrónico" name="userEmail">
                            <button class="newEmail" onclick="addInput()">Añadir</button>
                        </li>
                    </ol>
                </div>
                <div class="box-btn">
                    <button class="submit" type="submit" name="login">Enviar</button>
                </div>                
            </form>
        </div>
    </body>
    <?php //include $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?> 
</html>