<?php require_once('../Connections/cnn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Gestor";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once("../Connections/funciones.php"); ?>


<?php
$criterio = getParam($_GET["criterio"], "");
$totalRows_tramites = 0;
?>
<?PHP
	 if ($criterio != "") {
		$query_tramites = "SELECT * FROM tramites WHERE NunTrm like ".sqlValue($criterio."%", "text")." ORDER BY IdTrm Desc";
		$tramites = mysql_query($query_tramites, $cnn) or die(mysql_error());
		$totalRows_tramites = mysql_num_rows($tramites);
				};
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_tramite = 5;
$pageNum_tramite = 0;
if (isset($_GET['pageNum_tramite'])) {
  $pageNum_tramite = $_GET['pageNum_tramite'];
}
$startRow_tramite = $pageNum_tramite * $maxRows_tramite;

mysql_select_db($database_cnn, $cnn);
$query_tramite = "SELECT * FROM tramites ORDER BY IdTrm Desc";
$query_limit_tramite = sprintf("%s LIMIT %d, %d", $query_tramite, $startRow_tramite, $maxRows_tramite);
$tramite = mysql_query($query_limit_tramite, $cnn) or die(mysql_error());
$row_tramite = mysql_fetch_assoc($tramite);

if (isset($_GET['totalRows_tramite'])) {
  $totalRows_tramite = $_GET['totalRows_tramite'];
} else {
  $all_tramite = mysql_query($query_tramite);
  $totalRows_tramite = mysql_num_rows($all_tramite);
}
$totalPages_tramite = ceil($totalRows_tramite/$maxRows_tramite)-1;

$queryString_tramite = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_tramite") == false && 
        stristr($param, "totalRows_tramite") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_tramite = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_tramite = sprintf("&totalRows_tramite=%d%s", $totalRows_tramite, $queryString_tramite);
?>
<?php $dia= $row_tramite['FePresen']; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/BaseAdmin.dwt" codeOutsideHTMLIsLocked="false" -->
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Administracion gestiones inmobiliarias</title>


<!-- InstanceEndEditable -->
<link rel="stylesheet" href="../css/2col_leftNav.css" type="text/css" />
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<style type="text/css">
<!--
.Estilo1 {color: #000033}
.Estilo4 {color: #666666}
-->
</style>
</head>
<!-- The structure of this file is exactly the same as 2col_rightNav.html;
     the only difference between the two is the stylesheet they use -->
<body>
	
<div id="masthead">
  <h1 class="Estilo1" id="siteName">Gestiones inmobiliarias </h1>
</div>
<!-- end masthead -->
<div id="content">
  <p id="pageName"><!-- InstanceBeginEditable name="Contenido" -->
  	
  <h1>Bienvenido a Administración de tramites...</h1>
  <div class="barramenu">
    
     <ul class="menu">
	
	<li class="extremos"><a href="index.php">Inicio</a></li>	

	<li><a href="tramites_add.php">Lista de tramite</a></li>
  	
        <li><a href="tramites_alta.php">Alta tramites</a></li>
    
	<li><a href="tipotrm_alta.php">A.tipo trm</a></li>
     
	<li><a href="prioridad_trm.php">A.prioridad</a></li>
     	
	<li><a href="tramites_gestion.php">Gestiones</a></li>      
    
    </ul>
  </div>

<hr>
<div class="element4">
  <h3>Buscar tramite</h3>  
  <form method="get" action="">
    <table>
     <td><input name="criterio" type="text" id="criterio" size="25" value="<?php echo $criterio; ?>" title="Ingresar numero de tramite"/></td> &nbsp; 
     <td><input class="cajabuscar" type="submit" id="btbuscar" value="Buscar" /></th>
    </table>
  </form>
</div>  

<?php if ($totalRows_tramites> 0) { ?>
<p><em>Total de Resultados: <?php echo $totalRows_tramites; ?></em></p>
    <hr>	
  	<div class="divEjemplo2"> 
    	<p>Datos Tramites</p>
        <table class="tablaingresos2">
        <tr style="color: #000">
      	<th>Nombre y Apellido</th>
        <th>Dni</th>
        <th>Lugar</th>
        <th>Tramite</th>
        <th>Prioridad</th>
        <th>Objeto</th>
        <th>Total</th>
        <th>Presentacion</h1></th>
        <th>Tramite - Ver</th>
        </tr>
<?php while ($row_tramites = mysql_fetch_assoc($tramites)) { ?>
      <tr>
	<?php $lugar = $row_tramites['Nomlug'];
	switch($lugar){
        case "Registro de la propiedad inmue":
	$nomlug="R.P.Inmueble.";
	     break;
	case 1:
        $nomlug="R.P.Inmueble.";
    	     break;
	case 2:
        $nomlug="D.G.Rentas";
             break;
        case 3:
    	$nomlug="Municipalida.C.C.";
   	     break;
};
?> 
     <form action="tramite_edit.php" method="post">
        <td><input size="10" value="<?php echo $row_tramites['NomCltes']; ?>" name="cliente"/></td>
        <td><input size="10" value="<?php echo $row_tramites['DniCltes']; ?>" name="dni"/></td>
        <td><input size="10" value="<?php echo $nomlug ?>" name="lugar" /></td>
        <td><input size="10" value="<?php echo $row_tramites['TpoTrm']; ?>" name="tipo" /></td>
        <td><input size="10" value="<?php echo $row_tramites['PrdTrm']; ?>" name="prioridad" /></td>
        <td><input size="10" value="<?php echo $row_tramites['Objeto']; ?>" name="objeto" /></td>
        <td><input size="10" value="<?php echo $row_tramites['ImpTrm']; ?>" name="total"  /></td>
        <td><input size="10" value="<?php echo $row_tramites['FePresen']; ?>" name="presentacion"  /></td>
        <td><input size="10" value="<?php echo $row_tramites['NunTrm']; ?>" name="numero" /><input size="3" value="Ver" type="submit"></td>
        </form>
      </tr>
      <?php } ?>
    </table>
    </div>
    <?php } ?>

<p>&nbsp;</p>
<h5><p align="left">Lista de los ultimos ingresos de tramites:</p></h5>
  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">Nº</td>
      <td class="tablapriincipal">Lugar</td>
      <td class="tablapriincipal">Tramite</td>
      <td class="tablapriincipal">Prioridad</td>
      <td class="tablapriincipal">Objeto</td>
      <td class="tablapriincipal">Cliente</td> 
      <td class="tablapriincipal">Ingreso</td>
      <td class="tablapriincipal">Numero</td>
      <td class="tablapriincipal">Escribano</td>
    </tr>
    <?php do { ?>
<?php $lugar = $row_tramite['Nomlug'];
switch($lugar){
	case "Registro de la propiedad inmue":
	$nomlug="R.P.Inmueble.";
     break;
	case 1:
    $nomlug="R.P.Inmueble.";
     break;
	case 2:
    $nomlug="D.G.Rentas";
     break;
	case 3:
    $nomlug="Municipalida.C.C.";
     break;
};
?>    
      <tr>
        <td><?php echo $row_tramite['IdTrm']; ?></td>
        <td><?php echo $nomlug ?></td>
        <td><?php echo $row_tramite['TpoTrm']; ?></td>
        <td><?php echo $row_tramite['PrdTrm']; ?></td>
        <td><?php echo $row_tramite['Objeto']; ?></td>
        <td><?php echo $row_tramite['NomCltes']; ?></td>
        <td><?php echo $row_tramite['FePresen'];?></td>
        <td><?php echo $row_tramite['NunTrm']; ?></td>
        <td><?php echo $row_tramite['NomUsu']; ?></td>
       
      </tr>
      <?php } while ($row_tramite = mysql_fetch_assoc($tramite)); ?>
  </table>
  <p>  
 <table class="paginacion">
    <tr>
      <td><?php if ($pageNum_tramite > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_tramite=%d%s", $currentPage, 0, $queryString_tramite); ?>"><img src="First.gif" /> Primero</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_tramite > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_tramite=%d%s", $currentPage, max(0, $pageNum_tramite - 1), $queryString_tramite); ?>"><img src="Previous.gif" /> Anterior</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_tramite < $totalPages_tramite) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_tramite=%d%s", $currentPage, min($totalPages_tramite, $pageNum_tramite + 1), $queryString_tramite); ?>">Siguiente  <img src="Next.gif" /></a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_tramite < $totalPages_tramite) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_tramite=%d%s", $currentPage, $totalPages_tramite, $queryString_tramite); ?>">&Uacute;ltimo  <img src="Last.gif" /></a>
      <?php } // Show if not last page ?></td>
    </tr>
  </table>
  </p>
  <!-- InstanceEndEditable --></p>
</div>
<!--end content -->
<div id="navBar">
  <div id="search">
    <form action="#">
      <label><?php include('../includes/cabeceraadmin.php');?></label>
    </form>
  </div>
  <div id="headlines"></div>
</div>
<!--end navbar -->
<div id="siteInfo"><a href="#">Tesis</a> | &copy;2014 Company Quodesh's Sistemas. </div>
<br />
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($tramite);
?>
