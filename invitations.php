
    <?php require $_SERVER["DOCUMENT_ROOT"].'/includes/header.php' ?>
        <div class="logo">
            <img src="media/Logotripcuenta.png">
        </div>

        <h1>INVITACIONES</h1>
        <div class="form">
            <p>Aquí podrás introducir el correo electrónicos de tus amigos/as con los que quieres compartir ésta experiencia en <? $tripName ?></p>
            <form id="form">
                <br>
                <label for="userEmail"><b>Correos electrónicos</b></label><br> 
                <input type="email" placeholder="Introduce correo electrónico" name="userEmail">
            </form>
            <button class="newEmail" onclick="addInput()">Añadir</button>    
            <div id="btn">
                <button class="submit" type="submit" name="login">Enviar</button>
            </div> 
        </div>
    <?php// require $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?> 
