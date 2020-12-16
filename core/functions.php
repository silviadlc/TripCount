<?php
	require $_SERVER["DOCUMENT_ROOT"].'/core/connection.php';
	session_start();

	// TODO: Sistema local para el usuario que haya iniciado sesión
	if(isset($_POST["userEmail"])){
		global $user;
		$_SESSION["usermail"] = $_POST["userEmail"];
		$user= $_SESSION["usermail"];
	}
		
	// Si la variable global de SESSION['alerts'] no esta asignada, se le asigna.
	if(!isset($_SESSION['alerts'])) {
		$_SESSION['alerts'] = array();
	}

	function getTitleDocument() {
		/**
		 * Esta función lo que realizará es obtener el título y evitar se tenga que realizar siempre un TITLE.
		 */
		$config = array(
			'/login.php' => 'Iniciar sesión - ',
			'/home.php' => 'Home - ',
			'/invitations.php' => 'Invitaciones -',
			'/index.php' => ''
		);

		if(isset($config[parse_url($_SERVER['REQUEST_URI'])['path']])) {
			return $config[parse_url($_SERVER['REQUEST_URI'])['path']];
		} else {
			return 'Página desconocida por el sistema - ';
		}
	}

	function getCSSdependingUrl() {
		/**
		 * Esta función lo que realizará es obtener CSS y evitar que se acumulen en el HEAD del header.php para evitar conflictos.
		 */
		$config = array(
			'/login.php' => '../css/login.css',
			'/home.php' => '../css/home.css',
			'/invitations.php' => '../css/invitations.css',
			'/index.php' => '../css/landing.css'
		);

		return $config[parse_url($_SERVER['REQUEST_URI'])['path']];
	}

	function notAllowedToEnterIfNotLogged() {
		/**
		 * Esta función lo que realizará es no dejar a los usuarios que NO esten logueados a diferentes páginas que pongamos en la variable de $config.
		 * Cualquiera página que este puesta, se "protegerá" y solo aceptará usuarios logueados si tienen '1' y si tienen '0' solo aceptará usuarios no logueados.
		 * Página que no este, se indicará como "neutral" y aceptará usuarios logueados y no logueados.
		 */
		$config = array(
			'/home.php' => '1',
			'/login.php' => '0'
		);

		if(isset($config[parse_url($_SERVER['REQUEST_URI'])['path']])) {
			if($config[parse_url($_SERVER['REQUEST_URI'])['path']] == 0) {
				if(@$_SESSION['userLogged']) {
					header('Location: /home.php');
					exit;
				}
			} else if($config[parse_url($_SERVER['REQUEST_URI'])['path']] == 1) {
				if(@!$_SESSION['userLogged']) {
					header('Location: /login.php');
					exit;
				}
			} else {
				die('Ha ocurrido un problema en la función y se te ha denegado el acceso.');
			}
		}
	}

	function showTravels($orderBy = 1) {
		global $conn, $usernameLogged;
		switch ($orderBy) {
			case 1:
				$sql = $conn->query("SELECT travels.* FROM travels LEFT JOIN travels_users ON travels.idTravel = travels_users.idTravel WHERE travels_users.idUsername = $usernameLogged ORDER BY travels.created DESC");
				break;

			case 2:
				$sql = $conn->query("SELECT travels.* FROM travels LEFT JOIN travels_users ON travels.idTravel = travels_users.idTravel WHERE travels_users.idUsername = $usernameLogged ORDER BY travels.updated DESC");
				break;
			
			default:
				die('Debes de seguir los valores estipulados del select.');
				break;
		}
		
		while ($row = $sql->fetch()) {
			echo '
			<tr>
				<td>'.$row['idTravel'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$row['description'].'</td>
				<td>'.getNameCurrency($row['currency']).'</td>
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

	function showAlert($type, $message, $manyErrors = array()) {
        if ($type == 'info'){
			echo '
		<div class="alert info">
			<div class="icon" onclick="closeAlert(this)"><span id="close">&times;</span></div>
			<div class="alert-content">
				<h2 class="alert-title">Información</h2>
				<p>'.$message.'</p>
			</div>
		</div>';
        } elseif ($type == 'danger') {
			echo '
		<div class="alert danger">
			<div class="icon" onclick="closeAlert(this)"><span id="close">&times;</span></div>
			<div class="alert-content">
				<h2 class="alert-title">¡Parece que ha habido un problema!</h2>
				<p>'.$message.'</p>
			</div>
		</div>';
		} elseif ($type == 'danger-m') {
			echo '
		<div class="alert danger">
			<div class="icon" onclick="closeAlert(this)"><span id="close">&times;</span></div>
			<div class="alert-content">
				<h2 class="alert-title">¡Parece que ha habido un problema!</h2>
				<p>'.$message.'</p>
				<ul>';
				foreach ($manyErrors as $error) {
					echo '<li>'.$error.'</li>';
				}
				echo '
				</ul>
			</div>
		</div>';
        } elseif ($type == 'success') {
			echo '
		<div class="alert success">
			<div class="icon" onclick="closeAlert(this)"><span id="close">&times;</span></div>
			<div class="alert-content">
				<h2 class="alert-title">¡Éxito!</h2>
				<p>'.$message.'</p>
			</div>
		</div>';
        } elseif ($type == 'warning') {
			echo '
		<div class="alert warning">
			<div class="icon" onclick="closeAlert(this)"><span id="close">&times;</span></div>
			<div class="alert-content">
				<h2 class="alert-title">Aviso</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse imperdiet lacus et tortor placerat mollis. Nam sapien mauris, rhoncus sit amet dapibus nec, convallis et metus. Praesent ut maximus sem.</p>
			</div>
		</div>';
        }
	}
	
	function getNameCurrency($currencyCode) {
		global $conn;
		$sql = $conn->query('SELECT name FROM currency WHERE code = "'.$currencyCode.'"');
		if($sql->rowCount() != 0) {
			$row = $sql->fetch();
			return $row['name'];
		} else {
			return false;
		}
	}

	function showCurrencyAsOptions() {
		global $conn;
		$sql = $conn->query('SELECT * FROM currency');
		while ($row = $sql->fetch()) {
			echo '<option value="'.$row['code'].'">'.$row['name'].' ('.$row['code'].')</option>';
		}
	}

	//function 