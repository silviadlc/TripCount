<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php' ?>
<div id="content">
	<h1 class="content-title">Home</h1>
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
		<form>
			<label for="orderTravels">Ordenar por: 
				<select name="orderTravels" id="orderTravels" onchange="selectRedirectOrderInput(this.value)">
					<option value="1" <?php if(!empty($_GET['order'])) { echo optionSelectedByGet($_GET['order'], 1); } ?>>Fecha de creación</option>
					<option value="2" <?php if(!empty($_GET['order'])) { echo optionSelectedByGet($_GET['order'], 2); } ?>>Fecha de última actualización</option>
				</select>
			</label>
		</form>
		<table border="1" width="100%">
			<tr>
				<td>#</td>
				<td>Nombre del viaje</td>
				<td>Descripción del viaje</td>
				<td>Moneda asignada al viaje</td>
			</tr>
			<?php
				if(!isset($_GET['order']) || !empty($_GET['order'])) {
					showTravels();
				} else {
					showTravels($_GET['order']);
				}
			?>
		</table>
	</div>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?>