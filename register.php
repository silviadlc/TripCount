	<?php	
    include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
	?>
        <div class="logo">
            <img src="../media/Logotripcuenta.png">
		</div>
		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendRegister'])) {

			}
		?>

        <form action="#" method="POST">
			<h1>REGISTRATE</h1>
			<label for="userEmail"><b>Correo electrónico</b></label><br>
			<input type="email" value="'.$email.'" name="userEmail" required><br><br>
			<label for="userEmail"><b>Correo electrónico</b></label><br>
			<input type="email" placeholder="Introduce correo electrónico" name="userEmail" required><br>
            
            <label for="password"><b>Contraseña</b></label><br>
			<input type="password" placeholder="Introduce una contraseña" size="20" id="password" name="password" onkeyup="passwordCheck()" required><br><br>
			
            <label for="password"><b>Repetir Contraseña</b></label><br>
			<input type="password" placeholder="Repite la contraseña" size="20" id="repassword" name="repassword" onkeyup="passwordCheck()" required><br><br>
			
            <input type="submit" name="sendRegister" id="sendRequest"/>              
		</form>