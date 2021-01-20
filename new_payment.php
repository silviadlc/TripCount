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
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['savePayment'])) {
           
            $data['idExpense'] = filter_var($_POST[''], FILTER_SANITIZE_NUMBER_INT);
            $data['idTravel'] = filter_var($_GET['idTravel'], FILTER_SANITIZE_NUMBER_INT);
            $data['travelDescription'] = filter_var($_POST['travelDescription'], FILTER_SANITIZE_STRING);
            $data['travelCurrency'] = filter_var($_POST['travelCurrency'], FILTER_SANITIZE_STRING);
                $sql = $conn->prepare('INSERT INTO expenses SET 
                    idExpense = ?,
                    idUsername = ?,
                    idTravel = ?,
                    reason = ?,
                    amount = ?,
                    created = ?
                ');

                $sql->bindParam(1, $data['idExpense']);
                $sql->bindParam(2, $localUser['idUsername']);
                $sql->bindParam(3, $data['idTravel']);
                $sql->bindParam(4, $data['travelDescription']);
                $sql->bindParam(5, $data['travelCurrency']);
                $sql->execute();

                $lastIdForTravel = $conn->lastInsertId();

                }
            }
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
    <form id="multifoto" method="post" action="new_payment.php" enctype="multipart/form-data">
        <label>Añadir factura/s: </label>
        <input type="file" id="files[]" name="files[]" multiple=""><br>
        <button type="submit" accesskey="s"><underline class="accesskey">S</underline>ubir archivos</button>
    </form>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?>