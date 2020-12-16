	<?php
		require $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
		
		
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendLogin'])) {
			$data['userEmail'] = filter_var($_POST['userEmail'], FILTER_SANITIZE_EMAIL, FILTER_SANITIZE_STRING);
			$data['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

			if(!empty($data['userEmail'])) {
				if(!filter_var($data['userEmail'], FILTER_VALIDATE_EMAIL)) {
					$alertsLocal[] = 'El correo electrónico no sigue los estandares de formato.';
				}
			} else {
				$alertsLocal[] = '¡Debes de rellenar el campo de correo electrónico!';
			}

			if(!empty($data['password'])) {
				$sql = $conn->prepare('SELECT email, password FROM users WHERE email = ?');
				$sql->bindParam(1, $data['userEmail']);
				$sql->execute();
				if($sql->rowCount() != 0) {
					$row = $sql->fetch(PDO::FETCH_ASSOC);
					if(!password_verify($data['password'], $row['password'])) {
						$alertsLocal[] = 'La contraseña introducida no concuerda con el correo electrónico.';
					}
				} else {
					$alertsLocal[] = 'El correo electrónico insertado no ha sido encontrado.';
				}
			} else {
				$alertsLocal[] = '¡Debes de rellenar el campo de la contraseña!';
			}

			if(@$alertsLocal) {
				showAlert('danger-m', 'No hemos podido iniciar sesión. Verifica los siguientes errores: ', $alertsLocal);
			} else {
				// Solicitamos la ID del usuario y se la asignamos a una variable de sesión para que indique que el usuario se ha logueado.
				$sql = $conn->prepare('SELECT idUsername FROM users WHERE email = ?');
				$sql->bindParam(1, $data['userEmail']);
				$sql->execute();

				$row = $sql->fetch(PDO::FETCH_ASSOC);

				$_SESSION['userLogged'] = $row['idUsername'];
				$_SESSION['alerts'][] = array('type' => 'success', 'message' => 'Has iniciado sesión correctamente.');
				echo '<meta http-equiv="refresh" content="0; url=/home.php">';
				exit;
			}
        }
    ?>
        <div class="logo">
            <img src="../media/Logotripcuenta.png">
        </div>

        <form action="#" method="POST">
            <h1>INICIAR SESIÓN</h1>
            <label for="userEmail"><b>Correo electrónico</b></label><br>
            <input type="email" placeholder="Introduce email" name="userEmail"><br><br>
            <label for="password"><b>Contraseña</b></label><br>
            <input type="password" placeholder="Introduce contraseña" id="password" name="password"><br><br>
            <button type="submit" name="sendLogin">Entrar</button>                
		</form>
		
		<?php
			require $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php';
		?>