	<?php 
		require $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
	?>

		<div id="breadcrumb">
			<ul class="breadcrumb">
			<li><a href="home.php">Home</a></li>
			<li><a href="invitations.php">Invitaciones</a></li>
			</ul>
		</div>
        <div class="logo">
			<img src="media/Logotripcuenta.png">
		</div>

		<h1>INVITACIONES</h1>
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

	<?php
		if(!isset($_GET['idTravel']) || empty($_GET['idTravel'])) {
			die(showAlert('danger', 'No puedes acceder: Debes de ser dueño de un viaje para invitarlos.'));
		} else {
			if(!is_numeric($_GET['idTravel'])) {
				die(showAlert('danger', 'La ID del viaje esta formateada incorrectamente.'));
			} else if(is_numeric($_GET['idTravel'])) {
				$sql = $conn->prepare('SELECT * FROM travels WHERE idTravel = ?');
				$sql->bindParam(1, $_GET['idTravel']); $sql->execute();

				if($sql->rowCount() != 0) {
					$travelContent = $sql->fetch();
					if($travelContent['idUsername'] != $localUser['idUsername']) {
						die(showAlert('danger', 'No eres el dueño de este viaje y no puedes invitar.'));
					} 
				} else {
					die(showAlert('danger', 'La ID no concuerda con ningún viaje.'));
				}
			} else {
				die(showAlert('danger', 'Error desconocido.'));
			}
		}

		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendInvitations'])) {
			foreach($_POST['emailsList'] as $email) {
				$email = filter_var($email, FILTER_SANITIZE_EMAIL);
				if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$duplicatedInvitation = $conn->query('SELECT idInvitation FROM invitations WHERE idTravel = "'.$_GET['idTravel'].'" AND email = "'.$email.'"');
					if($duplicatedInvitation->rowCount() == 0) {
						if(usernameExistsByEmail($email)) {
							$emailBody = '<p>¡Hola, '.$email.'!</p>
							<p>Has recibido este correo electrónico debido a que has sido invitado para entrar al viaje "'.$travelContent['name'].'" por '.$localUser['name'].' '.$localUser['lastName'].'.
							<br><a href="//sildelax.tk/completeInvitation.php?id='.$conn->lastInsertId().'"><button style="
							background-color: #008CBA; 
							border: none;
							color: white;
							padding: 15px 32px;
							text-align: center;
							text-decoration: none;
							display: inline-block;
							font-size: 16px;
							margin: 4px 2px;
							cursor: pointer; margin-top: 20px;">¡Clic aquí para aceptar la invitación!</button></a>';
						} else {
							$emailBody = '<p>¡Hola, '.$email.'!</p>
							<p>Has recibido este correo electrónico debido a que has sido invitado para entrar al viaje "'.$travelContent['name'].'" por '.$localUser['name'].' '.$localUser['lastName'].'.</p>
							<p>Para poder aceptar la invitación del viaje, deberás de registrarte primero.</p>
							<br><a href="//sildelax.tk/register.php?inv='.$email.'"><button style="
							background-color: #469241; 
							border: none;
							color: white;
							padding: 15px 32px;
							text-align: center;
							text-decoration: none;
							display: inline-block;
							font-size: 16px;
							margin: 4px 2px;
							cursor: pointer; margin-top: 20px;">REGISTRARME</button></a>
								
							<a href="//sildelax.tk/completeInvitation.php?id='.$conn->lastInsertId().'"><button style="
							background-color: #008CBA; 
							border: none;
							color: white;
							padding: 15px 32px;
							text-align: center;
							text-decoration: none;
							display: inline-block;
							font-size: 16px;
							margin: 4px 2px;
							cursor: pointer; margin-top: 20px;">Clic aquí para aceptar la invitación.</button></a>';
						}
							
						if(!send_email($email, '¡Hey! Te han invitado para entrar a "'.$travelContent['name'].'"', $emailBody)) {
							$alertsLocal[] = 'La invitación para el correo electrónico "'.$email.'" no ha podido ser enviado correctamente.';
						} else {
							$sql = $conn->query('INSERT INTO invitations SET idUsername = "'.$localUser['idUsername'].'", idTravel = "'.$_GET['idTravel'].'", email = "'.$email.'"');
							if(!$sql) {
								$alertsLocal[] = 'La invitación para el correo electrónico "'.$email.'" no ha podido ser enviado correctamente.';
							}
						}
					} else {
						$alertsLocal[] = 'El correo electrónico "'.$email.'" ya tiene una invitación pendiente.';
					}
				} else {
					$alertsLocal[] = 'El correo electrónico "'.$email.'" tiene un formato inválido y se ha saltado.';
				}
				sleep(1);
			}

			if(@$alertsLocal) {
				$_SESSION['alerts'][] = array('type' => 'danger-m', 'message' => array('Ha surgido algunos problemas al enviar las invitaciones. ¡Dale un vistazo!', $alertsLocal));
				echo '<meta http-equiv="refresh" content="0; url=">';
				exit;
			} else {
				$_SESSION['alerts'][] = array('type' => 'success', 'message' => 'Se han enviado las invitaciones correctamente.');
				echo '<meta http-equiv="refresh" content="0; url=/home.php">';
				exit;
			}
		}

		echo usernameExistsByEmail('eduardsnchez@gmail.com');
	?>
        <div class="form">
            <p>¡Aquí puedes introducir el correo electrónico de los amigos/as con los que quieres compartir ésta experiencia en el viaje "<?php echo $travelContent['name']; ?>"!</p>
            <form id="form" method="POST" action="#">
                <br>
				<label for="userEmail"><b>Correos electrónicos</b></label><br><br>
				<div id="newEmailList">
					<input id="inputEmail" name="emailsList[]" type="email" placeholder="Introduce un correo electrónico" required>
					<i id="addInput" class="fas fa-plus-square" onclick="addInput()" accesskey="+"></i>
					<i id="deleteInput" class="fas fa-minus-square" onclick="deleteInput()" accesskey="-"></i>
				</div>
				<div id="btn">
					<button class="submit" type="submit" name="sendInvitations" accesskey="n">E<underline class="accesskey">n</underline>viar</button>
				</div> 
            </form>
        </div>
    <?php require $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?> 
