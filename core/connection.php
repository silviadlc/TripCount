<?php
	require $_SERVER["DOCUMENT_ROOT"].'/core/config.php';

	if(DEBUG) {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	/**
	 * Este archivo realiza la conexión hacía la base de datos recibiendo las constantes del archivo de config.php
	 */
	try {
		$conn = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		if(DEBUG) {
			die('La conexión ha fallado con el siguiente error: '.$e->getMessage());
		}
	}