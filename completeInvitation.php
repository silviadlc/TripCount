<?php
	require $_SERVER["DOCUMENT_ROOT"].'/core/functions.php';
	if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
		$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
		$sql = $conn->query('SELECT * FROM invitations WHERE idInvitation = "'.$id.'"');
		if($sql->rowCount() != 0) {
			$invitation = $sql->fetch();
			if($invitation['email'] == $localUser['email']) {
				$conn->query('INSERT INTO travels_users SET idUsername = "'.$localUser['idUsername'].'", idTravel = "'.$invitation['idTravel'].'"');
				$conn->query('DELETE FROM invitations WHERE idInvitation = "'.$invitation['idInvitation'].'"');
				$_SESSION['alerts'][] = array('type' => 'success', 'message' => 'Has confirmado la invitación exitosamente.');
				header('Location: /home.php');
			} else {
				die('Sin permisos.');
			}
		} else {
			die('Sin permisos.');
		}
	} else {
		die('Sin permisos.');
	}