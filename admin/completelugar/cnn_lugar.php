<?php
$Nomlug = $_POST['Nomlug'];
$conexion = new mysqli('localhost','root','12061','tesis');
$consulta = "SELECT IdLug FROM lugares WHERE Nomlug = '$Nomlug'";
$result = $conexion->query($consulta);
 
$respuesta = new stdClass();
if($result->num_rows > 0){
    $fila = $result->fetch_array();
    $respuesta->IdLug = $fila['IdLug'];
}
echo json_encode($respuesta);
?>