<?php
	function obtenerConexion() {
		$db = new mysqli('localhost', 'root', '12061', 'tesis');

		if($db->connect_errno > 0){
		    die('Unable to connect to database [' . $db->connect_error . ']');
		}

		return $db;	
	}

	function cerrarConexion($db, $query) {
		$query->free();
		$db->close();
	}

	function ejecutarQuery($db, $sql) {
		if(!$resultado = $db->query($sql)){
		    die('There was an error running the query [' . $db->error . ']');
		}

		return $resultado;
	}
?>