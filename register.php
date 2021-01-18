	<?php	include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
            if(isset($_POST["email"])){
                $email = $_POST["email"];

                 //Procederemos a hacer una consulta que buscara el correo del usuario
                $searchMail = "SELECT email from users WHERE email='$email'";

                 //Realizamos la consulta y anadimos $connection, ya que es la variable que creamos en nuestro archivo connection.php
                $result = $conn->query($searchMail);

                 //SI SI EXISTE una fila, quiere decir QUE SI ESTA EL CORREO EN LA BASE DE DATOS
                if($result == $email) {
                   header("Location: login.php");
                } else{
                    header("Location: register.php");
                }

            }
	?>
<script>
function passwordCheck(){
    let passwordValue = document.regist.password.value;
    let repasswordValue = document.regist.repassword.value;

    if (passwordValue == repasswordValue){
         document.getElementById('sendRequest').disabled = false;
    }else{
         document.getElementById('sendRequest').disabled = true;
    }

}
</script>
        <div class="logo">
            <img src="../media/Logotripcuenta.png">
        </div>

        <form action="#" method="POST" name="regist">
            <h1>REGISTRO</h1>
            <label for="email"><b>Correo electrónico</b></label><br>
            <?php 
            //pruebas
            if(isset($_POST["email"])){
                echo $email;
            } else {
                echo "hola";
            }?>

            <input type="email" placeholder="Introduce un correo electrónico" name="email" id="email"><br><br>
            <label for="password"><b>Contraseña</b></label><br>
            <input type="password" placeholder="Introduce una contraseña" size="20" id="password" name="password" onkeyup="passwordCheck()"><br><br>
            <label for="password"><b>Repetir Contraseña</b></label><br>
            <input type="password" placeholder="Repite la contraseña" size="20" id="repassword" name="repassword" onkeyup="passwordCheck()"><br><br>
            <button type="submit" name="sendRequest" id="sendRequest" disabled>Entrar</button>                
		</form>