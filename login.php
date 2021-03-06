	<?php	
		include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
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
		<!-- alert-content -->
		<?php
			if(isset($_SESSION['alerts']) && !empty($_SESSION['alerts'])) {
				foreach($_SESSION['alerts'] as $alert) {
					if($alert['type'] == 'danger-m') {
						showAlert($alert['type'], $alert['message'][0], $alert['message'][1]);
					} else {
						showAlert($alert['type'], $alert['message']);
					}
				}
				unset($_SESSION['alerts']);
			}
		?>
		<!-- /alert-content -->
		<div class="form">
			<form action="#" method="POST">
				<h1>INICIAR SESIÓN</h1><br><br>
				<?php 
					if(isset($_SESSION['email'])){
						$email=$_SESSION['email'];
						echo'<label for="userEmail"><b>Correo electrónico</b></label><br>
						<input type="email" value="'.$email.'" name="userEmail"><br><br>';
					}else{
						echo'<label for="userEmail"><b>Correo electrónico</b></label><br>
						<input type="email" placeholder="Introduce correo electrónico" name="userEmail"><br><br>';
			}?>
				<label for="password"><b>Contraseña</b></label><br>
				<input type="password" placeholder="Introduce contraseña" id="password" name="password"><br><br>
				<button type="submit" name="sendLogin" accesskey="n">E<underline class="accesskey">n</underline>trar</button>                
			</form>
		</div>
		