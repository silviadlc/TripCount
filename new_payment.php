<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php'; 
   	if(!isset($_GET['idTravel']) || empty($_GET['idTravel'])) {
        die(showAlert('danger', 'Se te ha denegado el acceso.'));
    } else {
        if(!is_numeric($_GET['idTravel'])) {
            die(showAlert('danger', 'La ID del viaje esta formateada incorrectamente.'));
        } else if(is_numeric($_GET['idTravel'])) {
            $sql = $conn->prepare('SELECT * FROM travels WHERE idTravel = ?');
			$sql->bindParam(1, $_GET['idTravel']); $sql->execute();

            if($sql->rowCount() != 0) {
				// Si el viaje existe, realiza un fetch donde añadirá en una variable toda la información sobre el viaje.
				$travelContent = $sql->fetch();
            } else {
                die(showAlert('danger', 'La ID no concuerda con ningún viaje.'));
            }
        } else {
            die(showAlert('danger', 'Error desconocido.'));
        }
    }
?>

<div id="breadcrumb">
	<ul class="breadcrumb">
    <li><a href="home.php">Home</a></li>
	<li><a href="#"><?php echo $travelContent['name']; ?></a></li>
    <li><a href="new_payment.php">Pagos</a></li>
	</ul>
</div>
<div class="logo">
	<img src="../media/Logotripcuenta.png">
</div>
<div id="content">
    <h1 class="content-title">Introducir pago</h1>
    <div class="addPayment">
    <p class="destiny">Viaje: <b><?php echo $travelContent['name']; ?></b></p>
        <form id="payment" method="post" autocomplete="off">
            <label for="amount">
                Cantidad del pago:
                <input type="number" name="amount" placeholder="Inserta aquí la cantidad" id="amount"/><br>
            </label>
            <label for="travelUsers">
                Usuario que ha pagado:
                <select id="paymentUser" name="paymentUser">
                <?php
                    foreach ($_SESSION['users']['username'] as $user_data) {
                        list($id, $user) = explode(" - ", $user_data);
                        echo "<option name='$id'>$user</option>";
                    }
                ?>     
                </select><br>
            </label>
            <div id="usersCheck">
                <h3>Pago avanzado</h3>
                <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                <label for="vehicle1">"usuario"</label>
                <input type="number" id="payment1" name="payment1" placeholder="Cantidad a pagar">
                <label for="payment1" id="euro">€</label>
            </div><br>
            <button type="submit" name="advanced" accesskey="p" onclick="showFormCustom('usersCheck');"><underline class="accesskey">P</underline>ago avanzado</button>
            <button type="submit" name="savePayment" accesskey="g"><underline class="accesskey">G</underline>uardar pago</button>     
        </form>
    </div>
	<?php
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uploadFileToPayment'])) {
			$_FILES['filesInput'] = reArrayFiles($_FILES['filesInput']);
			foreach($_FILES['filesInput'] as $file) {
				$continueToUpload = true;
				$targetFile = 'media/bills/' . basename($file['name']);
				$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

				if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'pdf') {
					$alertsLocal[] = '[Archivo '.htmlspecialchars($file['name']).'] Solo se permite las extensiones jpg, jpeg, png y pdf para subir.';
					$continueToUpload = false;
				}

				if ($file["size"] > 500000) {
					$alertsLocal[] = '[Archivo '.htmlspecialchars($file['name']).'] El archivo es muy grande para poder subirlo.';
					$continueToUpload = false;
				}

				if($continueToUpload) {
					
					if(move_uploaded_file($file['tmp_name'], $targetFile)) {
						$uploadFileSql = $conn->prepare('INSERT INTO pictures SET idUsername = ?, idExpense = ?, url = ?');
						$uploadFileSql->bindParam(1, $localUser['idUsername']);
						$uploadFileSql->bindParam(2, $travelContent['idTravel']);
						$uploadFileSql->bindParam(3, $targetFile);

						if($uploadFileSql->execute()) {
							echo 'ok';
						}
					}
				}
			}

			if(@$alertsLocal) {
				showAlert('danger-m', 'No ha sido posible la subida de algunos archivos.', $alertsLocal);
			}
		}
	?>
    <form id="multifoto" method="post" action="#" enctype="multipart/form-data">
        <label>Añadir factura/s: </label>
        <input type="file" id="files" name="filesInput[]" multiple><br>
        <button name="uploadFileToPayment" type="submit" accesskey="s"><underline class="accesskey">S</underline>ubir archivos</button>
    </form>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?>