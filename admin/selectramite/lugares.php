<?php
	include("C:\AppServ\www\Tesis\admin\selectramite\db.php");

	function obtenerTodosLosPaises() {
		$paises = array();
		$sql = "SELECT IdLug, Nomlug 
			    FROM lugares"; 

		$db = obtenerConexion();
		$result = ejecutarQuery($db, $sql);

		while($row = $result->fetch_assoc()){
			$pais = new pais($row['IdLug'], $row['Nomlug']);
		    array_push($paises, $pais);
		}

		cerrarConexion($db, $result);

		return $paises;
	}

	class pais {
		public $id;
		public $nombre;

		function __construct($id, $nombre) {
			$this->id = $id;
			$this->nombre = $nombre;
		}
	}
?>
