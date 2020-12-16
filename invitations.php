    <?php require $_SERVER["DOCUMENT_ROOT"].'/includes/header.php' ?>
        <div class="logo">
            <img src="media/Logotripcuenta.png">
        </div>

        <h1>INVITACIONES</h1>
        <div class="form">
            <p>Aquí puedes introducir el correo electrónico de los amigos/as con los que quieres compartir ésta experiencia en <? $tripName ?></p>
            <form id="form">
                <br>
                <label for="userEmail"><b>Correos electrónicos</b></label><br><br> 
                <input type="email" placeholder="Introduce correo electrónico" name="userEmail">
            </form>
            <button class="newEmail" onclick="addInput()">Añadir</button>    
            <div id="btn">
                <button class="submit" type="submit" name="submit" onclick="sendEmails()">Enviar</button>
            </div> 
        </div>
    <?php require $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?> 
