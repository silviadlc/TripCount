<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php' ?>

<div id="content">
	<h1 class="content-title">Home</h1>
	<!-- alert-content -->

	<!-- /alert-content -->
	<div class="travels">
		<form>
			<label for="orderTravels">Ordenar por: 
				<select name="orderTravels" id="orderTravels" onchange="selectRedirectOrderInput(this.value)">
					<option value="1" <?php echo optionSelectedByGet($_GET['order'], 1); ?>>Fecha de creación</option>
					<option value="2" <?php echo optionSelectedByGet($_GET['order'], 2); ?>>Fecha de última actualización</option>
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
				if(!isset($_GET['order']) && !empty($_GET['order'])) {
					showTravels();
				} else {
					showTravels($_GET['order']);
				}
			?>
		</table>
	</div>
</div>