<?php
$Nomlug = $_GET['term'];
 
$conexion = new mysqli('localhost','root','12061','tesis');
 
$consulta = "SELECT Nomlug FROM lugares WHERE Nomlug LIKE '%$Nomlug%'";
 
$result = $conexion->query($consulta);
 
if($result->num_rows > 0){
    while($fila = $result->fetch_array()){
        $nombres[] = $fila['Nomlug'];
    }
echo json_encode($nombres);
}
?>