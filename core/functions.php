<?php
	require $_SERVER["DOCUMENT_ROOT"].'/core/connection.php';
	session_start();

	if(isset($_SESSION['userLogged']) && !empty($_SESSION['userLogged'])) {
		$sql = $conn->query('SELECT * FROM users WHERE idUsername = "'.$_SESSION['userLogged'].'"');
		$localUser = $sql->fetch();
	}
		
	// Si la variable global de SESSION['alerts'] no esta asignada, se le asigna.
	if(!isset($_SESSION['alerts'])) {
		$_SESSION['alerts'] = array();
	}

	function getUsernameById($id) {
		global $conn;
		$sql = $conn->query('SELECT name FROM users WHERE idUsername = "'.$id.'"');
		$userLocal = $sql->fetch();
		return $userLocal['name'];
	}

	function usernameExistsByEmail($stringToFilter) {
		global $conn;
		$stringToFilter = filter_var($stringToFilter, FILTER_SANITIZE_STRING);
		$sql = $conn->query("SELECT idUsername FROM users WHERE email = '$stringToFilter'");

		if($sql->rowCount() == 1) {
			return true;
		}

		return false;
	}

	function getTitleDocument() {
		/**
		 * Esta función lo que realizará es obtener el título y evitar se tenga que realizar siempre un TITLE.
		 */
		$config = array(
			'/login.php' => 'Iniciar sesión - ',
			'/home.php' => 'Home - ',
			'/invitations.php' => 'Invitaciones -',
			'/edit.php' => 'Editar viaje - ',
			'/' => '¡Bienvenid@ a TripCount!',
			'/index.php' => '¡Bienvenid@ a TripCount!',
			'/register.php' => 'Registrarme - '
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
			'/index.php' => '../css/landing.css',
			'/edit.php' => '../css/edit.css',
			'/register.php' => '../css/register.css',
			'/new_payment.php' => '../css/new_payment.css');

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
			'/login.php' => '0',
			'/completeInvitation.php' => '1',
			'/index.php' => '1'
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
		global $conn, $localUser;
		switch ($orderBy) {
			case 1:
				$travelsToShow = $conn->query('SELECT travels.* FROM travels LEFT JOIN travels_users ON travels.idTravel = travels_users.idTravel WHERE travels_users.idUsername = "'.$localUser['idUsername'].'" ORDER BY travels.idTravel DESC');
				break;

			case 2:
				$travelsToShow = $conn->query('SELECT travels.* FROM travels LEFT JOIN travels_users ON travels.idTravel = travels_users.idTravel WHERE travels_users.idUsername = "'.$localUser['idUsername'].'" ORDER BY travels.updated DESC');
				break;
			
			default:
				die('Debes de seguir los valores estipulados del select.');
				break;
		}
		while ($row = $travelsToShow->fetch()) {
			$expenses = $conn->query('SELECT * FROM expenses WHERE idTravel="'.$row['idTravel'].'"');
			$amount = 0;
			while ($fields = $expenses->fetch()) {
				$amount = $amount + $fields['amount'];
				$reason = $fields['reason'];
			}
			
			echo '
			<tr>
				<td>'.$row['idTravel'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$row['description'].'</td>
				<td>'.getNameCurrency($row['currency']).'</td>
				<td>Hace '.timeAgo($row['created']).'</td>';
				
			if(!empty($row['updated'])) {
				echo '<td>Actualizado hace '.timeAgo($row['updated']).'</td>';
			} else {
				echo '<td>No ha habido ninguna actualización</td>';
			}

				echo '<td><a href="#?idTravel='.$row['idTravel'].'" onclick="showDetails('.$row['idTravel'].')">
					<i class="fas fa-chevron-circle-down"></i>
					<a href="edit.php?idTravel='.$row['idTravel'].'">
					<i class="far fa-edit"></i></a>
					</td>
			</tr>';
			
				echo '<tr class="details details'.$row['idTravel'].'">
					<td></td>
					<td>Fecha: '.$row['created'].'</td>
					<td>Total gastos: '.$amount.'</td>
					<td></td>
					<td><a href="new_payment.php">
					<button class="addExpenses" accesskey="a"><underline class="accesskey">A</underline>ñadir gasto</button></td>
					<td><a href="#">
					<button class="manageUsers" accesskey="g"><underline class="accesskey">G</underline>estionar usuarios</button></a></td>
					<td><a href="balance.php">
					<button class="balance" accesskey="b"><underline class="accesskey">B</underline>alance</button></a></td>
					<tr><td>
						<ul>
							<li>Concepto: '.$reason/* , $fields['amount']. */'</li>
						</ul>
					</td></tr>
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
			<div class="icon" onclick="closeAlert(this)"><span id="close">?</span></div>
			<div class="alert-content">
				<h2 class="alert-title">Información</h2>
				<p>'.$message.'</p>
			</div>
		</div>';
        } elseif ($type == 'danger') {
			echo '
		<div class="alert danger">
			<div class="icon" onclick="closeAlert(this)"><span id="close">X</span></div>
			<div class="alert-content">
				<h2 class="alert-title">¡Parece que ha habido un problema!</h2>
				<p>'.$message.'</p>
			</div>
		</div>';
		} elseif ($type == 'danger-m') {
			echo '
		<div class="alert danger">
			<div class="icon" onclick="closeAlert(this)"><span id="close"></span></div>
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
			<div class="icon" onclick="closeAlert(this)"><span id="close">X</span></div>
			<div class="alert-content">
				<h2 class="alert-title">¡Éxito!</h2>
				<p>'.$message.'</p>
			</div>
		</div>';
        } elseif ($type == 'warning') {
			echo '
		<div class="alert warning">
			<div class="icon" onclick="closeAlert(this)"><span id="close">X</span></div>
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

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	function send_email($to, $subject, $body) {
		//Import the PHPMailer class into the global namespace

		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		date_default_timezone_set('Etc/UTC');

		//Create a new PHPMailer instance
		require_once $_SERVER['DOCUMENT_ROOT'].'/PHPMailer/src/Exception.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/PHPMailer/src/PHPMailer.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/PHPMailer/src/SMTP.php';
		$mail = new PHPMailer();
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// SMTP::DEBUG_OFF = off (for production use)
		// SMTP::DEBUG_CLIENT = client messages
		// SMTP::DEBUG_SERVER = client and server messages
		$mail->SMTPDebug = SMTP::DEBUG_OFF;
		//Set the hostname of the mail server
		$mail->Host = 'smtp.gmail.com';
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 587;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = 'tricuentapp@gmail.com';
		//Password to use for SMTP authentication
		$mail->Password = "ZC0lGsZX!JE1gpD!zjcd2HZR*L!a%";
		//Set who the message is to be sent from
		$mail->setFrom('no-reply@tripcount.com', 'TripCount');
		//Set who the message is to be sent to
		$mail->addAddress($to);
		//Set the subject line
		$mail->Subject = $subject;
		$mail->Body = '
		<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td rowspan="3" style="width: calc(100-300px); padding: 10px;"></td>
				<td style="height: 100px; color: #FFF; width: 600px; padding: 10px;box-shadow: 0px 0px 20px -10px rgba(0,0,0,0.75);" align="center" bgcolor="#01068C">
					<h1>Tripcount</h1>
				</td>
				<td rowspan="3" style="width: calc(100-300px); padding: 10px;"></td>
			</tr>
			<tr style="background: url(https://i.imgur.com/vwWRCP.png); background-size: cover;">
				<td style="padding: 10px;box-shadow: 0px 0px 20px -10px rgba(0,0,0,0.75);">
					'.$body.'
				</td>
			</tr>
			<tr>
				<td style="color: #FFF; padding: 10px;box-shadow: 0px 0px 20px -10px rgba(0,0,0,0.75);" bgcolor="#CB5A04">
					<div>
						<em>Este mensaje ha sido enviado automáticamente.</em>
						<div style="float: right;">
							2020-'.date('Y').' © Equipo T
						</div>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>';
		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
		//Replace the plain text body with one created manually
		//$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		if (!$mail->send()) {
			return false;
		} else {
			return true;
		}
	}
	
	function timeAgo($since) {
		$since = time() - strtotime($since);

		$chunks = array(
			array(60 * 60 * 24 * 365 , 'año'),
			array(60 * 60 * 24 * 30 , 'mes'),
			array(60 * 60 * 24 * 7, 'semana'),
			array(60 * 60 * 24 , 'día'),
			array(60 * 60 , 'hora'),
			array(60 , 'minuto'),
			array(1 , 'segundo')
		);
	
		for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];
			if (($count = floor($since/$seconds)) != 0) {
				break;
			}
		}
		
		if($name == 'mes') {
			$print = ($count == 1) ? '1 '.$name : "$count {$name}es";
		} else {
			$print = ($count == 1) ? '1 '.$name : "$count {$name}s";
		}

		return $print;
	}