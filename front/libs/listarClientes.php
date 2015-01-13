<?php require_once('../../Connections/cnn.php'); 
session_start();
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_usuarios = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuarios = $_SESSION['MM_Username'];
}
mysql_select_db($database_cnn, $cnn);
$query_usuarios = sprintf("SELECT * FROM usuarios WHERE EmailUsu = %s", GetSQLValueString($colname_usuarios, "text"));
$usuarios = mysql_query($query_usuarios, $cnn) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$totalRows_usuarios = mysql_num_rows($usuarios);
?>

<?php require_once('C:/AppServ/www/Tesis/front/libs/conexion.php');
$cn= Conectarse();
$listado= mysql_query("select * from tramites where IdUsuario=". $_SESSION['IdUsu'] ,$cn) ; ?>

<script type="text/javascript" language="javascript" src="../front/js/jslistadoclientes.js"></script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_clientes">
<thead>
<tr>
<th>Nº</th><!–Estado–>
<th>Cliente</th>
<th>Dni</th>
<th>Tramite</th>
<th>Escribano</th>
</tr>
</thead>

<tfoot>
<tr>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>
</tfoot>

<tbody>
<?php
while($reg = mysql_fetch_array($listado))
{
echo '<tr>';
echo '<td >'.mb_convert_encoding($reg['IdTrm'], "UTF-8").'</td>';
echo '<td>'.mb_convert_encoding($reg['NomCltes'], "UTF-8").'</td>';
echo '<td>'.mb_convert_encoding($reg['DniCltes'], "UTF-8").'</td>';
echo '<td>'.mb_convert_encoding($reg['TpoTrm'], "UTF-8").'</td>';
echo '<td>'.mb_convert_encoding($reg['NomUsu'], "UTF-8").'</td>';
echo '</tr>';

}
?>
<tbody>
</table>
<?php
mysql_free_result($usuarios);
?>