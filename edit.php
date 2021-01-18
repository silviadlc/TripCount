<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php'; 
   	if(!isset($_GET['idTravel']) || empty($_GET['idTravel'])) {
        die(showAlert('danger', 'No puedes acceder: Debes de ser dueño de un viaje para invitarlos.'));
    } else {
        if(!is_numeric($_GET['idTravel'])) {
            die(showAlert('danger', 'La ID del viaje esta formateada incorrectamente.'));
        } else if(is_numeric($_GET['idTravel'])) {
            $sql = $conn->prepare('SELECT * FROM travels WHERE idTravel = ?');
            $sql->bindParam(1, $_GET['idTravel']); $sql->execute();

            if($sql->rowCount() != 0) {
				// Si el viaje existe, realiza un fetch donde añadirá en una variable toda la información sobre el viaje.
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
?>

<div id="breadcrumb"
	<ul class="breadcrumb">
    <li><a href="home.php">Home</a></li>
	<li><a href="#"><?php echo $travelContent['name'] ?></a></li>
    <li><a href="edit.php">Editar viaje</a></li>
	</ul>
</div>
<div class="logo">
	<img src="../media/Logotripcuenta.png">
</div>
<div id="content">
	<?php
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveTravel'])) {
			$data['travelDescription'] = filter_var($_POST['travelDescription'], FILTER_SANITIZE_STRING);
			if(empty($data['travelDescription'])) {
				$alertsLocal[] = 'La descripción del viaje no puede estar vacía.';
			}

			if(!empty($_POST['emailsList'])) {
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
								$alertsLocalForEmail[] = 'La invitación para el correo electrónico "'.$email.'" no ha podido ser enviado correctamente.';
							} else {
								$sql = $conn->query('INSERT INTO invitations SET idUsername = "'.$localUser['idUsername'].'", idTravel = "'.$_GET['idTravel'].'", email = "'.$email.'"');
								if(!$sql) {
									$alertsLocalForEmail[] = 'La invitación para el correo electrónico "'.$email.'" no ha podido ser enviado correctamente.';
								}
							}
						} else {
							$alertsLocalForEmail[] = 'El correo electrónico "'.$email.'" ya tiene una invitación pendiente.';
						}
					} else {
						$alertsLocalForEmail[] = 'El correo electrónico "'.$email.'" tiene un formato inválido y se ha saltado.';
					}
					sleep(1);
				}
			}

			if(@$alertsLocal || @$alertsLocalForEmail) {
				if(@$alertsLocal) {
					showAlert('danger-m', 'No se ha podido editar el viaje. Verifica los siguientes errores: ', $alertsLocal);
				}

				if(@$alertsLocalForEmail) {
					showAlert('danger-m', 'No se ha podido enviar las siguientes invitaciones para el viaje. Verifica los siguientes errores: ', $alertsLocalForEmail);
				}
			} else {
				$sql = $conn->prepare('UPDATE travels SET description = ?, updated = NOW() WHERE idTravel = "'.$travelContent['idTravel'].'"');
				$sql->bindParam(1, $data['travelDescription']);

				if($sql->execute()) {
					$_SESSION['alerts'][] = array('type' => 'success', 'message' => 'Se ha modificado correctamente el viaje #'.$travelContent['idTravel'].'.');
					echo '<meta http-equiv="refresh" content="1; url=/home.php">';
					exit;
				}
			}
		}
	?>

    <h1 class="content-title">Editar viaje</h1>
    <form id="editTravel" autocomplete="off" method="POST">
        <label for="travelName">
            Nombre del viaje:
            <input type="text" placeholder="Inserta aquí el nombre del viaje." id="travelName" maxlength="30" value="<?php echo $travelContent['name']; ?>" readonly/><br>
        </label>
        <label for="travelDescription">
            Descripción del viaje:
            <input type="text" name="travelDescription" placeholder="Inserta aquí la descripción del viaje." id="travelDescription" value="<?php echo $travelContent['description'] ?>" /><br>
        </label>
        <label for="travelUsers">
			Añadir usuarios:<br>
            <div id="newEmailList">	
				<?php
					$sql = $conn->query('SELECT email FROM users INNER JOIN travels_users ON users.idUsername = travels_users.idUsername AND travels_users.idTravel = "'.$travelContent['idTravel'].'"');
					if($sql->rowCount() != 0) {
						while($row = $sql->fetch()) {
							echo '<input id="inputEmail" type="text" value="'.$row['email'].'" readonly>';
						}
					}
				?>
				<i id="addInput" class="fas fa-plus-square" onclick="addInput()"></i>
				<i id="deleteInput" class="fas fa-minus-square" onclick="deleteInput()"></i>
			</div>
        </label>
        <button type="submit" name="saveTravel" accesskey="g"/><underline class="accesskey">G</underline>uardar viaje</button>
    </form>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?>