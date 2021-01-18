	<?php	
    include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
            if(isset($_POST["userEmail"])){

                $_SESSION["email"] = $_POST["userEmail"];
                $email=$_SESSION["email"];

                 //Procederemos a hacer una consulta que buscara el correo del usuario
                $searchMail = "SELECT count(*) total from users WHERE email='$email'";

                 //Realizamos la consulta y anadimos $connection, ya que es la variable que creamos en nuestro archivo connection.php
                $result = $conn->query($searchMail);
                $row = $result->fetchAll(PDO::FETCH_ASSOC);
                $count = $row['total'];
                 //SI SI EXISTE una fila, quiere decir QUE SI ESTA EL CORREO EN LA BASE DE DATOS
                if($count==0) {
                   header("Location: login.php");
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
            <?php 
                if(isset($_SESSION['email'])){
                    echo'<label for="userEmail"><b>Correo electrónico</b></label><br>
                    <input type="email" value="'.$email.'" name="userEmail" required><br><br>';
                }else{
                    echo'<label for="userEmail"><b>Correo electrónico</b></label><br>
                    <input type="email" placeholder="Introduce correo electrónico" name="userEmail" required><br><br>';
            }?>
            <label for="password"><b>Contraseña</b></label><br>
            <input type="password" placeholder="Introduce una contraseña" size="20" id="password" name="password" onkeyup="passwordCheck()" required><br><br>
            <label for="password"><b>Repetir Contraseña</b></label><br>
            <input type="password" placeholder="Repite la contraseña" size="20" id="repassword" name="repassword" onkeyup="passwordCheck()" required><br><br>
            <button type="submit" name="sendRequest" id="sendRequest" disabled>Entrar</button>                
		</form>