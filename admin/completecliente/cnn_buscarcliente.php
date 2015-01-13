<?php
$nombre = $_GET['term'];
 
$conexion = new mysqli('localhost','root','12061','tesis');
 
$consulta = "SELECT NomCltes FROM clientes WHERE NomCltes LIKE '%$nombre%'";
 
$result = $conexion->query($consulta);
 
if($result->num_rows > 0){
    while($fila = $result->fetch_array()){
        $nombres[] = $fila['NomCltes'];
    }
echo json_encode($nombres);
}
?>