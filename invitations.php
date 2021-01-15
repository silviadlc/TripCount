	<?php 
		require $_SERVER["DOCUMENT_ROOT"].'/includes/header.php';
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
						$sql = $conn->query('INSERT INTO invitations SET idUsername = "'.$localUser['idUsername'].'", idTravel = "'.$_GET['idTravel'].'", email = "'.$email.'"');
						if($sql) {
							if(send_email($email, '¡Hey! Te han invitado para entrar a "'.$travelContent['name'].'"',
								'<p>¡Hola, '.$email.'!</p>
								<p>Has recibido este correo electrónico debido a que has sido invitado para entrar al viaje "'.$travelContent['name'].'" por '.$localUser['name'].' '.$localUser['lastName'].'.
								<br><a href="//tricuenta.tk/completeInvitation.php?id='.$conn->lastInsertId().'"><button style="
								background-color: #008CBA; 
								border: none;
								color: white;
								padding: 15px 32px;
								text-align: center;
								text-decoration: none;
								display: inline-block;
								font-size: 16px;
								margin: 4px 2px;
								cursor: pointer; margin-top: 20px;">¡Clic aquí para aceptar la invitación!</button></a>')) {

							}
						}
					} else {
						$alertsLocal[] = 'El correo electrónico "'.$email.'" ya tiene una invitación pendiente.';
					}
				} else {
					$alertsLocal[] = 'El correo electrónico "'.$email.'" tiene un formato inválido y se ha saltado.';
				}
				sleep(2);
			}

			if(@$alertsLocal) {
				$_SESSION['alerts'][] = array('type' => 'danger-m', 'message' => array('Ha surgido algunos problemas al enviar las invitaciones. ¡Dale un vistazo!', $alertsLocal));
				echo '<meta http-equiv="refresh" content="0; url=/home.php">';
				exit;
			} else {
				$_SESSION['alerts'][] = array('type' => 'success', 'message' => 'Se han enviado las invitaciones correctamente.');
				echo '<meta http-equiv="refresh" content="0; url=/home.php">';
				exit;
			}
		}
	?>
		<div id="breadcrumb"
			<ul class="breadcrumb">
			<li><a href="home.php">Home</a></li>
			<li><a href="invitations.php">Invitaciones</a></li>
			</ul>
		</div>
        <div class="logo">
			<img src="media/Logotripcuenta.png">
		</div>

        <h1>INVITACIONES</h1>
        <div class="form">
            <p>¡Aquí puedes introducir el correo electrónico de los amigos/as con los que quieres compartir ésta experiencia en el viaje "<?php echo $travelContent['name']; ?>"!</p>
            <form id="form" method="POST" action="#">
                <br>
				<label for="userEmail"><b>Correos electrónicos</b></label><br><br>
				<div id="newEmailList">
					<input id="inputEmail" name="emailsList[]" type="email" placeholder="Introduce un correo electrónico" required>
					<i class="fas fa-plus-square" onclick="addInput()"></i>
					<i class="fas fa-minus-square" onclick="deleteInput()"></i>
				</div>
				<div id="btn">
					<button class="submit" type="submit" name="sendInvitations" accesskey="e"><underline class="accesskey">E</underline>nviar</button>
				</div> 
            </form>
        </div>
    <?php require $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?> 
