	<?php	
    include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
	?>
        <div class="logo">
            <img src="../media/Logotripcuenta.png">
		</div>
		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendRegister'])) {
				$data['username']['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
				$data['username']['surname'] = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
				$data['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL, FILTER_SANITIZE_STRING);
				$data['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
				$data['repeatPassword'] = filter_var($_POST['repeatPassword'], FILTER_SANITIZE_STRING);

				if(!empty($data['username']['name'])) {
					if(strlen($data['username']['name']) >= 31) {
						$alertsLocal[] = 'El nombre del usuario no cumple con los requisitos de longitud.';
					}
				} else {
					$alertsLocal[] = 'El nombre del usuario esta vacío.';
				}

				if(!empty($data['username']['surname'])) {
					if(strlen($data['username']['surname']) >= 31) {
						$alertsLocal[] = 'El apellido del usuario no cumple con los requisitos de longitud.';
					}
				} else {
					$alertsLocal[] = 'El apellido del usuario esta vacío.';
				}

				if(!empty($data['email'])) {
					if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
						$sql = $conn->query('SELECT idUsername FROM users WHERE email = "'.$data['email'].'"');
						if($sql->rowCount() != 0) {
							$alertsLocal[] = 'El correo electrónico insertado esta ocupado por otro usuario.';
						}
					} else {
						$alertsLocal[] = 'Se ha insertado un email incorrecto.';
					}
				} else {
					$alertsLocal[] = 'El campo de correo electrónico esta vacío.';
				}

				if(empty($data['password'])) {
					$alertsLocal[] = 'El campo de la contraseña se encuentra vacío.';
				}

				if(empty($data['repeatPassword'])) {
					$alertsLocal[] = 'El campo de repetir la contraseña se encuentra vacío.';
				}

				if(!empty($data['password']) && !empty($data['repeatPassword'])) {
					if($data['password'] != $data['repeatPassword']) {
						$alertsLocal[] = 'Las contraseñas no coinciden. Vuelve a intentarlo.';
					}
				}

				if(@$alertsLocal) {
					showAlert('danger-m', 'No has podido registrarte correctamente. Verifica los siguientes errores: ', $alertsLocal);
				} else {
					$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
					$sql = $conn->prepare('INSERT INTO users SET name = ?, lastName = ?, email = ?, password = ?');
					$sql->bindParam(1, $data['username']['name']);
					$sql->bindParam(2, $data['username']['surname']);
					$sql->bindParam(3, $data['email']);
					$sql->bindParam(4, $data['password']);
					if($sql->execute()) {
						$_SESSION['alerts'][] = array('type' => 'success', 'message' => 'Te has registrado correctamente. Ya puedes iniciar sesión con tus credenciales.');
						echo '<meta http-equiv="refresh" content="0; url=/login.php">';
						exit;
					}
				}
			}
		?>

        <form action="#" method="POST" autocomplete="off">
			<h1>REGISTRO</h1>
			<label for="username">
				Nombre y apellido del usuario:<br>
				<input id="username" name="name" type="text" placeholder="Nombre" style="width: calc(50% - 2px);" maxlength="30" required/>
				<input id="username" name="surname" type="text" placeholder="Apellido" style="width: calc(50% - 2px);" maxlength="30" required/>
			</label>

			<label for="email">
				Correo electrónico:
				<input id="email" name="email" type="email" placeholder="Correo electrónico" maxlength="40" required/> 
			</label>

			<label for="password">
				Contraseña:<br>
				<input id="password" name="password" type="password" placeholder="Contraseña" style="width: calc(50% - 2px);" maxlength="100" required/>
				<input id="password" name="repeatPassword" type="password" placeholder="Repite la contraseña" style="width: calc(50% - 2px);" required/>
			</label>
			
            <button type="submit" name="sendRegister" id="sendRequest" accesskey="e"><underline class="accesskey">E</underline>nviar</button>              
		</form>