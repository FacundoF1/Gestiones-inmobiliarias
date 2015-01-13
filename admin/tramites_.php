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

mysql_select_db($database_cnn, $cnn);
$query_cliente = "SELECT IdCltes, NomCltes, CuitCltes, UNIX_TIMESTAMP(FealtaCltes) AS FealtaCltes FROM clientes ORDER BY IdCltes DESC LIMIT 1";
$cliente = mysql_query($query_cliente, $cnn) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
?>
<?php
$drestantes= ("+1 days");

//Fecha de inicio, alojada en la BD:
$fecha_inicio= $row_cliente['FealtaCltes'];
//final: dentro de 5 dias
$fecha_final=strtotime("$drestantes", $fecha_inicio);
//¿Cuanto queda?
$quedan_dias=ceil(($fecha_final-time())/86400);
//damos un poco de formato a los dias restantes...
switch($quedan_dias){
  case 0:
    $dias="hoy";
    break;
  case 1:
    $dias="mañana";
    break;
  default:
    $dias="dentro de ".$quedan_dias." días";
}
?>




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
    <p>
    <h1>Bienvenido a Administración de clientes...</h1>    	
    
        <p>
        Tu oferta de prueba durante 90 días, que comenzó el 
		<?php echo date("d / m / Y", $fecha_inicio)?>, finaliza <?php echo $dias?>, 
        el <?php echo date("d / m / Y", $fecha_final)?></p>
        <p></p>
    	<table width="100%" border="0">
    	  <tr>
    	    <td width="26%">Cliente</td>
    	    <td width="18%">Dni</td>
    	    <td width="26%">Fecha de alta</td>
    	    <td width="30%">Edici&oacute;n</td>
  	    </tr>
    	  <tr>
    	    <td><?php echo $row_cliente['NomCltes']; ?></td>
    	    <td><?php echo $row_cliente['CuitCltes']; ?></td>
    	    <td><?php echo date("d / m / Y", $fecha_inicio) ?></td>
    	    <td>Editar</td>
  	    </tr>
  	  </table>
  <p>&nbsp;</p>
		<ul class="mi-menu">
        	<li><a href="clientes_alta.php"> Clientes</a></li>
            <ul><li type="circle"></li>
            </ul>
        </ul>
        <ul class="mi-menu">
        	<li><a href="clientes_alta.php"> Categorias</a></li>
        </ul>
         <ul class="mi-menu">
        	<li><a href="clientes_alta.php"> Categorias</a></li>
        </ul>
        <ul class="mi-menu">
        	<li><a href="clientes_alta.php"> Categorias</a></li>
        </ul>
        
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    
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
mysql_free_result($cliente);
?>
