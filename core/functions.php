<?php
	require $_SERVER["DOCUMENT_ROOT"].'/core/connection.php';

	// TODO: Sistema local para el usuario que haya iniciado sesión
	$usernameLogged = 2; // ID del usuario local.

	function showTravels($orderBy = 1) {
		global $conn, $usernameLogged;
		switch ($orderBy) {
			case 1:
				$orderBy = 'ORDER BY travels.idTravel DESC';
				break;

			case 2:
				$orderBy = 'ORDER BY travels.updated DESC';
				break;
			
			default:
				die('Debes de seguir los values estipulados del select.');
				break;
		}
		
		
		$sql = $conn->query("SELECT travels.* FROM travels LEFT JOIN travels_users ON travels.idTravel = travels_users.idTravel WHERE travels_users.idUsername = $usernameLogged $orderBy");
		while ($row = $sql->fetch()) {
			echo '
			<tr>
				<td>'.$row['idTravel'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$row['description'].'</td>
				<td>'.$row['currency'].'</td>
			</tr>';
		}

	}

	function optionSelectedByGet($getValue, $getCompared) {
		/**
		 * $getValue - Obtendrá como se llamará el value necesitado.
		 * $getCompared - Se le dará el valor que tiene el usuario.
		 */

		if($getValue == $getCompared) {
			return 'selected';
		} else {
			return '';
		}
	}