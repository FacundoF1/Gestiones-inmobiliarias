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
<?php require_once('../Connections/cnn.php'); ?>
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

$maxRows_tramites = 5;
$pageNum_tramites = 0;
if (isset($_GET['pageNum_tramites'])) {
  $pageNum_tramites = $_GET['pageNum_tramites'];
}
$startRow_tramites = $pageNum_tramites * $maxRows_tramites;

mysql_select_db($database_cnn, $cnn);
$query_tramites = "SELECT IdTrm, Nomlug, PrdTrm, NomCltes, 
				   UNIX_TIMESTAMP(FePresen) AS FePresen
				   FROM tramites 
				   ORDER BY IdTrm DESC";
$query_limit_tramites = sprintf("%s LIMIT %d, %d", $query_tramites, $startRow_tramites, $maxRows_tramites);
$tramites = mysql_query($query_limit_tramites, $cnn) or die(mysql_error());
$row_tramites = mysql_fetch_assoc($tramites);

if (isset($_GET['totalRows_tramites'])) {
  $totalRows_tramites = $_GET['totalRows_tramites'];
} else {
  $all_tramites = mysql_query($query_tramites);
  $totalRows_tramites = mysql_num_rows($all_tramites);
}
$totalPages_tramites = ceil($totalRows_tramites/$maxRows_tramites)-1;
?>
<?php $fecha= $fe_inhabiles;?>
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
  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">IdTrm</td>
      <td class="tablapriincipal">Nomlug</td>
      <td class="tablapriincipal">PrdTrm</td>
      <td class="tablapriincipal">NomCltes</td>  
      <td class="tablapriincipal">FePresen</td>
      <td class="tablapriincipal">Falta</td>  
      <td class="tablapriincipal">Finaliza</td>
    </tr>
 

   <?php do { ?>

<?php
$fecha_inicio= $row_tramites['FePresen'];
$prioridad= $row_tramites['PrdTrm'];
$semana= date("l", $fecha_inicio);
switch($semana){
  case "Monday":
    $nomsemana="Lunes";
   break;
  case "Tuesday":
    $nomsemana="Martes";
   break;	
  case "Wednesday":
    $nomsemana="Miércoles";
   break;
  case "Thursday":
    $nomsemana="Jueves";
    break;
  case "Friday":
    $nomsemana="Viernes";
    break;
  case "Saturday":
    $nomsemana="Sabado";
   break;
  case "Sunday":
    $nomsemana="Domingo";
   break;
}
?>
<?php
switch($prioridad){
  case "En el día":
    $Lunes="+0 days";
    break;
  case "Super Urgente":
    $Lunes="+3 days";
    break;
  case "Especial":
    $Lunes="+3 days";
    break;
  case "Urgente":
    $Lunes="+12 days";
    break;
  case "Urgente DGR":
    $Lunes="+5 days";
    break;
  case "Comun":
    $Lunes="+30 days";
    break;
}
if ($nomsemana == 'Lunes') {
//Fecha de inicio, alojada en la BD:
//final: dentro de 5 dias
$fecha_final=strtotime("$Lunes", $fecha_inicio);
//¿Cuanto queda?
$quedan_dias=ceil(($fecha_final-time())/86400);
//damos un poco de formato a los dias restantes...
switch($quedan_dias){
  case 0:
    $dia="Hoy";
    break;
  case 1:
    $dia="Mañana";
    break;
  default:
    $dia= "".$quedan_dias." Dìas";
}
}
elseif ($nomsemana == 'Martes') {
	switch($prioridad){
  case "En el día":
    $Martes="+0 days";
    break;
  case "Super Urgente":
    $Martes="+3 days";
    break;
  case "Especial":
    $Martes="+3 days";
    break;
  case "Urgente":
    $Martes="+14 days";
    break;
  case "Urgente DGR":
    $Martes="+7 days";
    break;  
  case "Comun":
    $Martes="+35 days";
    break;
}
//Fecha de inicio, alojada en la BD:
//final: dentro de 5 dias
$fecha_final=strtotime("$Martes", $fecha_inicio);
//¿Cuanto queda?
$quedan_dias=ceil(($fecha_final-time())/86400);
//damos un poco de formato a los dias restantes...
switch($quedan_dias){
  case 0:
    $dia="Hoy";
    break;
  case 1:
    $dia="Mañana";
    break;
  default:
    $dia= "".$quedan_dias." Dìas";
}
}
elseif ($nomsemana == 'Miércoles') {
	switch($prioridad){
  case "En el día":
    $Miercoles="+0 days";
    break;
  case "Super Urgente":
    $Miercoles="+5 days";
    break;
  case "Especial":
    $Miercoles="+5 days";
    break;
  case "Urgente":
    $Miercoles="+14 days";
    break;
  case "Urgente DGR":
    $Miercoles="+7 days";
    break;
  case "Comun":
    $Miercoles="+41 days";
    break;
}
//Fecha de inicio, alojada en la BD:
//final: dentro de 5 dias
$fecha_final=strtotime("$Miercoles", $fecha_inicio);
//¿Cuanto queda?
$quedan_dias=ceil(($fecha_final-time())/86400);
//damos un poco de formato a los dias restantes...
switch($quedan_dias){
  case 0:
    $dia="Hoy";
    break;
  case 1:
    $dia="Mañana";
    break;
  default:
    $dia= "".$quedan_dias." Dìas";
}
}
elseif ($nomsemana == 'Jueves') {
	switch($prioridad){
  case "En el día":
    $Jueves="+0 days";
    break;
  case "Super Urgente":
    $Jueves="+5 days";
    break;
  case "Especial":
    $Jueves="+5 days";
    break;
  case "Urgente":
    $Jueves="+14 days";
    break;
  case "Urgente DGR":
    $Jueves="+7 days";
    break;
  case "Comun":
    $Jueves="+41 days";
    break;
}
//Fecha de inicio, alojada en la BD:
//final: dentro de 5 dias
$fecha_final=strtotime("$Jueves", $fecha_inicio);
//¿Cuanto queda?
$quedan_dias=ceil(($fecha_final-time())/86400);
//damos un poco de formato a los dias restantes...
switch($quedan_dias){
  case 0:
    $dia="Hoy";
    break;
  case 1:
    $dia="Mañana";
    break;
  default:
    $dia= "".$quedan_dias." Dìas";
}
}
elseif ($nomsemana == 'Viernes') {
	switch($prioridad){
  case "En el día":
    $Viernes="+0 days";
    break;
  case "Super Urgente":
    $Viernes="+5 days";
    break;
  case "Especial":
    $Viernes="+5 days";
    break;
  case "Urgente":
    $Viernes="+14 days";
    break;
  case "Urgente DGR":
    $Viernes="+7 days";
    break;
  case "Comun":
    $Viernes="+41 days";
    break;
}
//Fecha de inicio, alojada en la BD:
//final: dentro de 5 dias
$fecha_final=strtotime("$Viernes", $fecha_inicio);
//¿Cuanto queda?
$quedan_dias=ceil(($fecha_final-time())/86400);
//damos un poco de formato a los dias restantes...
switch($quedan_dias){
  case 0:
    $dia="Hoy";
    break;
  case 1:
    $dia="Mañana";
    break;
  default:
    $dia= "".$quedan_dias." Dìas";
}
};
?>
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
 <tr>

        <td><?php echo $row_tramites['IdTrm']; ?></td>
        <td><?php echo $nomlug; ?></td>
        <td><?php echo $row_tramites['PrdTrm']; ?></td>  
        <td><?php echo $row_tramites['NomCltes']; ?></td>
        <td><?php echo $nomsemana.date(" d - m", $fecha_inicio)?></td>
        <td><b><em style="color:red"><?php echo $dia?></em></b></td>
        <td><?php echo date(" d- m- Y", $fecha_final)?></td>
      
</tr>      
      <?php } while ($row_tramites = mysql_fetch_assoc($tramites)); ?>
  </table>
<table class="paginacion">
    <tr>
      <td><?php if ($pageNum_tramites > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_tramites=%d%s", $currentPage, 0, $queryString_tramites); ?>"><img src="First.gif" /> Primero</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_tramites > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_tramites=%d%s", $currentPage, max(0, $pageNum_tramites - 1), $queryString_tramites); ?>"><img src="Previous.gif" /> Anterior</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_tramites < $totalPages_tramites) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_tramites=%d%s", $currentPage, min($totalPages_tramites, $pageNum_tramites + 1), $queryString_tramite); ?>">Siguiente  <img src="Next.gif" /></a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_tramites < $totalPages_tramites) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_tramites=%d%s", $currentPage, $totalPages_tramites, $queryString_tramites); ?>">&Uacute;ltimo  <img src="Last.gif" /></a>
          <?php } // Show if not last page ?></td>
    </tr>
  </table>
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
mysql_free_result($tramites);
?>
