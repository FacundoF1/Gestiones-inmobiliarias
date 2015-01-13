<?php
//crear una imagen
$imagen = imagecreate(80, 35);
//color de fondo
$fondo = imagecolorallocate($imagen, 11,22,33);
//rellenar imagen
ImageFill($imagen,50,0,$fondo);

//Imprimir imagen
header('content-type: image/png');
imagepng($imagen);
?>