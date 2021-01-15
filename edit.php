<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php' ?>
<div id="breadcrumb"
	<ul class="breadcrumb">
	<li><a href="/">Login / Registre</a></li>
	<li><a href="home.php">Home</a></li>
	<li><a href="#"></a></li>
    <li><a href="edit.php">Editar viaje</a></li>
	</ul>
</div>
<div class="logo">
	<img src="../media/Logotripcuenta.png">
</div>
<div id="content">
    <h1 class="content-title">Editar viaje</h1>
    <form id="editTravel" autocomplete="off">
        <label for="travelName">
            Nombre del viaje:
            <input type="text" name="travelName" placeholder="Inserta aquí el nombre del viaje." id="travelName" /><br>
        </label>
        <label for="travelDescription">
            Descripción del viaje:
            <input type="text" name="travelDescription" placeholder="Inserta aquí la descripción del viaje." id="travelDescription" /><br>
        </label>
        <label for="travelUsers">
            Añadir usuarios:
            <select id="travelUsers" name="travelUsers">
                <option selected disabled></option>
            </select><br>
        </label>
        <button type="submit" name="saveTravel" accesskey="g"/><underline class="accesskey">G</underline>uardar viaje</button>
    </form>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/footer.php' ?>