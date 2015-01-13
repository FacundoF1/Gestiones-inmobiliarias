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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_usuario = 5;
$pageNum_usuario = 0;
if (isset($_GET['pageNum_usuario'])) {
  $pageNum_usuario = $_GET['pageNum_usuario'];
}
$startRow_usuario = $pageNum_usuario * $maxRows_usuario;

mysql_select_db($database_cnn, $cnn);
$query_usuario = "SELECT * FROM usuarios";
$query_limit_usuario = sprintf("%s LIMIT %d, %d", $query_usuario, $startRow_usuario, $maxRows_usuario);
$usuario = mysql_query($query_limit_usuario, $cnn) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);

if (isset($_GET['totalRows_usuario'])) {
  $totalRows_usuario = $_GET['totalRows_usuario'];
} else {
  $all_usuario = mysql_query($query_usuario);
  $totalRows_usuario = mysql_num_rows($all_usuario);
}
$totalPages_usuario = ceil($totalRows_usuario/$maxRows_usuario)-1;

$queryString_usuario = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_usuario") == false && 
        stristr($param, "totalRows_usuario") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_usuario = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_usuario = sprintf("&totalRows_usuario=%d%s", $totalRows_usuario, $queryString_usuario);
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
  	<p><h1>Bienvenido a Administración de usuarios...
  
  </h1>
 <div class="barramenu">
   <ul class="menu">
     <li><a href="index.php">Inicio</a></li>
     <li><a href="usuarios_alta.php">Alta usuarios</a></li>
   </ul>
  </div>
  <p align="center">&nbsp;</p>
  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">IdUsuario</td>
      <td class="tablapriincipal">NomUsu</td>
      <td class="tablapriincipal">ApeUsu</td>
      <td class="tablapriincipal">DniUsu</td>
      <td class="tablapriincipal">PassUsu</td>
      <td class="tablapriincipal">TelMovil</td>
      <td class="tablapriincipal">TelFijo</td>
      <td class="tablapriincipal">NomCat</td>
      <td class="tablapriincipal">EmailUsu</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_usuario['IdUsuario']; ?></td>
        <td><?php echo $row_usuario['NomUsu']; ?></td>
        <td><?php echo $row_usuario['ApeUsu']; ?></td>
        <td><?php echo $row_usuario['DniUsu']; ?></td>
        <td><?php echo $row_usuario['PassUsu']; ?></td>
        <td><?php echo $row_usuario['TelMovil']; ?></td>
        <td><?php echo $row_usuario['TelFijo']; ?></td>
        <td><?php echo $row_usuario['NomCat']; ?></td>
        <td><?php echo $row_usuario['EmailUsu']; ?></td>
      </tr>
      <?php } while ($row_usuario = mysql_fetch_assoc($usuario)); ?>
  </table>
  <p>
  <table border="0">
    <tr>
      <td><?php if ($pageNum_usuario > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_usuario=%d%s", $currentPage, 0, $queryString_usuario); ?>">Primero</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_usuario > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_usuario=%d%s", $currentPage, max(0, $pageNum_usuario - 1), $queryString_usuario); ?>">Anterior</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_usuario < $totalPages_usuario) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_usuario=%d%s", $currentPage, min($totalPages_usuario, $pageNum_usuario + 1), $queryString_usuario); ?>">Siguiente</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_usuario < $totalPages_usuario) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_usuario=%d%s", $currentPage, $totalPages_usuario, $queryString_usuario); ?>">&Uacute;ltimo</a>
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
mysql_free_result($usuario);
?>
