<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php' ?>
<div id="breadcrumb"
	<ul class="breadcrumb">
	<li><a href="home.php">Home</a></li>
	</ul>
</div>
<div class="logo">
	<img src="../media/Logotripcuenta.png">
</div>
<div id="content">
	<h1 class="content-title">Home</h1>
	<?php
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createTravel'])) {
			$data['travelName'] = filter_var($_POST['travelName'], FILTER_SANITIZE_STRING);
			$data['travelDescription'] = filter_var($_POST['travelDescription'], FILTER_SANITIZE_STRING);
			$data['travelCurrency'] = filter_var($_POST['travelCurrency'], FILTER_SANITIZE_STRING);

			if(empty($data['travelName'])) {
				$alertsLocal[] = 'El campo del nombre del viaje esta vacío.';
			}

			if(empty($data['travelDescription'])) {
				$alertsLocal[] = 'El campo de la descripción del viaje esta vacío.';
			}

			if(!empty($data['travelCurrency'])) {
				if(!getNameCurrency($data['travelCurrency'])) {
					$alertsLocal[] = 'No se ha encontrado correctamente la currency.';
				}
			} else {
				$alertsLocal[] = 'El campo del tipo de la moneda del viaje esta vacío.';
			}

			if(@$alertsLocal) {
				$_SESSION['alerts'][] = array('type' => 'danger-m', 'message' => array('No hemos podido crear el viaje. Verifique los siguientes errores:', $alertsLocal));
			} else {
				$sql = $conn->prepare('INSERT INTO travels SET 
					idUsername = ?,
					name = ?,
					description = ?,
					currency = ?,
					created = NOW(),
					updated = NOW()
				');
				$sql->bindParam(1, $localUser['idUsername']);
				$sql->bindParam(2, $data['travelName']);
				$sql->bindParam(3, $data['travelDescription']);
				$sql->bindParam(4, $data['travelCurrency']);
				$sql->execute();

				$lastIdForTravel = $conn->lastInsertId();

				$sql2 = $conn->query('INSERT INTO travels_users SET idUsername = '.$localUser['idUsername'].', idTravel = "'.$lastIdForTravel.'"');

				if($sql && $sql2) {
					header('Location: /invitations.php?idTravel='.$lastIdForTravel);
					exit;
				}
			}
		}
	?>
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
	<div class="travels">
		<div id="controlsTravel">
			<form autocomplete="off">
				<label for="orderTravels">Ordenar por: 
					<select name="orderTravels" id="orderTravels" onchange="selectRedirectOrderInput(this.value)">
						<option value="1" <?php if(!empty($_GET['order'])) { echo optionSelectedByGet($_GET['order'], 1); } ?>>Fecha de creación</option>
						<option value="2" <?php if(!empty($_GET['order'])) { echo optionSelectedByGet($_GET['order'], 2); } ?>>Fecha de última actualización</option>
					</select>
				</label>
			</form>
			<button class="add" onclick="showFormCustom('createTravel');" accesskey="+">+</button>
		</div>
		<table border="1" width="100%">
			<tr>
				<th>#</th>
				<th>Nombre del viaje</th>
				<th>Descripción del viaje</th>
				<th>Moneda asignada al viaje</th>
				<th>Acciones</th>
			</tr>
			<?php
				if(!isset($_GET['order'])) {
					showTravels();
				} else {
					showTravels($_GET['order']);
				}
			?>
		</table>
		<div id="controlsTravel">
			<form autocomplete="off">
				<label for="orderTravels">Ordenar por: 
					<select name="orderTravels" id="orderTravels" onchange="selectRedirectOrderInput(this.value)">
						<option value="1" <?php if(!empty($_GET['order'])) { echo optionSelectedByGet($_GET['order'], 1); } ?>>Fecha de creación</option>
						<option value="2" <?php if(!empty($_GET['order'])) { echo optionSelectedByGet($_GET['order'], 2); } ?>>Fecha de última actualización</option>
					</select>
				</label>
			</form>
			<button class="add" onclick="showFormCustom('createTravel');">+</button>
		</div>
	</div>
	<br><br>				
	<form id="createTravel" method="POST" action="#" autocomplete="off">
		<fieldset>
			<legend>Crear un nuevo viaje</legend>
			<label for="travelName">
				Nombre del viaje:
				<input type="text" name="travelName" placeholder="Inserta aquí el nombre del viaje." id="travelName" />
			</label>
			<label for="travelDescription">
				Descripción del viaje:
				<input type="text" name="travelDescription" placeholder="Inserta aquí la descripción del viaje." id="travelDescription" />
			</label>
			<label for="travelCurrency">
				Tipo de moneda del viaje:
				<select id="travelCurrency" name="travelCurrency">
					<option selected disabled>Selecciona la moneda</option>
					<?php
						showCurrencyAsOptions();
					?>
				</select>
			</label>
			<button type="submit" name="createTravel" accesskey="c"/><underline class="accesskey">C</underline>rear viaje</button>
			<!-- <input type="reset" value="Restablecer"/> -->
		</fieldset>
	</form>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?>