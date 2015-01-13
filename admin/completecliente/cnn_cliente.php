<?php
$nombre = $_POST['nombre'];
$conexion = new mysqli('localhost','root','12061','tesis');
$consulta = "SELECT CuitCltes FROM clientes WHERE NomCltes = '$nombre'";
$result = $conexion->query($consulta);
 
$respuesta = new stdClass();
if($result->num_rows > 0){
    $fila = $result->fetch_array();
    $respuesta->dni = $fila['CuitCltes'];
}
echo json_encode($respuesta);
?>