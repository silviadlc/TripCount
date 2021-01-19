<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php'; 
?>

<div id="breadcrumb">
	<ul class="breadcrumb">
    <li><a href="home.php">Home</a></li>
    <li><a href="new_payment.php">Pagos</a></li>
	</ul>
</div>
<div class="logo">
	<img src="../media/Logotripcuenta.png">
</div>
<div id="content">
    <h1 class="content-title">Introducir pago</h1>
    <div class="addPayment">
    <p class="destiny">Viaje: <b><?php $travelContent['name'];?></b></p>
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