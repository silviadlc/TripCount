<?php include $_SERVER["DOCUMENT_ROOT"].'/includes/header.php'; 
   /*  if(!isset($_GET['idTravel']) || empty($_GET['idTravel'])) {
        die(showAlert('danger', 'No puedes acceder: Debes de ser dueño de un viaje para invitarlos.'));
    } else {
        if(!is_numeric($_GET['idTravel'])) {
            die(showAlert('danger', 'La ID del viaje esta formateada incorrectamente.'));
        } else if(is_numeric($_GET['idTravel'])) {
            $sql = $conn->prepare('SELECT * FROM travels WHERE idTravel = ?');
            $sql->bindParam(1, $_GET['idTravel']); $sql->execute();

            if($sql->rowCount() != 0) {
                $travelContent = $sql->fetch();
                if($travelContent['idUsername'] != $localUser['idUsername']) {
                    die(showAlert('danger', 'No eres el dueño de este viaje y no puedes invitar.'));
                } else {
                    $sql = $conn->query('SELECT * FROM users WHERE idTravel = "'.$_SESSION['idTravel'].'"');
                    $localUser = $sql->fetch();
                }
            } else {
                die(showAlert('danger', 'La ID no concuerda con ningún viaje.'));
            }
        } else {
            die(showAlert('danger', 'Error desconocido.'));
        }
    } */
?>

<div id="breadcrumb"
	<ul class="breadcrumb">
    <li><a href="home.php">Home</a></li>
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