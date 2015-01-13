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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE clientes SET NomCltes=%s, CuitCltes=%s, TelmovilCltes=%s, TelfijoCltes=%s, EmailCltes=%s, CategCltes=%s, FealtaCltes=%s WHERE IdCltes=%s",
                       GetSQLValueString($_POST['NomCltes'], "text"),
                       GetSQLValueString($_POST['CuitCltes'], "text"),
                       GetSQLValueString($_POST['TelmovilCltes'], "int"),
                       GetSQLValueString($_POST['TelfijoCltes'], "int"),
                       GetSQLValueString($_POST['EmailCltes'], "text"),
                       GetSQLValueString($_POST['CategCltes'], "text"),
                       GetSQLValueString($_POST['FealtaCltes'], "date"),
                       GetSQLValueString($_POST['IdCltes'], "int"));

  mysql_select_db($database_cnn, $cnn);
  $Result1 = mysql_query($updateSQL, $cnn) or die(mysql_error());
}

$maxRows_clientes = 5;
$pageNum_clientes = 0;
if (isset($_GET['pageNum_clientes'])) {
  $pageNum_clientes = $_GET['pageNum_clientes'];
}
$startRow_clientes = $pageNum_clientes * $maxRows_clientes;

mysql_select_db($database_cnn, $cnn);
$query_clientes = "SELECT * FROM clientes ORDER BY IdCltes DESC";
$query_limit_clientes = sprintf("%s LIMIT %d, %d", $query_clientes, $startRow_clientes, $maxRows_clientes);
$clientes = mysql_query($query_limit_clientes, $cnn) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);

if (isset($_GET['totalRows_clientes'])) {
  $totalRows_clientes = $_GET['totalRows_clientes'];
} else {
  $all_clientes = mysql_query($query_clientes);
  $totalRows_clientes = mysql_num_rows($all_clientes);
}
$totalPages_clientes = ceil($totalRows_clientes/$maxRows_clientes)-1;

$queryString_clientes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_clientes") == false && 
        stristr($param, "totalRows_clientes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_clientes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_clientes = sprintf("&totalRows_clientes=%d%s", $totalRows_clientes, $queryString_clientes);
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
  	<h1>Bienvenido a Administración de clientes...  </h1>
   <div class="barramenu">
   <div class="menu">
   <ul>
     <li class="extremos1"><a href="index.php">Inicio</a></li>
     <li><a href="clientes_alta.php">Alta clientes</a></li>
   </ul>
   </div>
  </div>
  <p>&nbsp;</p>

  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">IdCltes</td>
      <td class="tablapriincipal">NomCltes</td>
      <td class="tablapriincipal">CuitCltes</td>
      <td class="tablapriincipal">TelmovilCltes</td>
      <td class="tablapriincipal">TelfijoCltes</td>
      <td class="tablapriincipal">EmailCltes</td>
      <td class="tablapriincipal">CategCltes</td>
      <td class="tablapriincipal">FealtaCltes</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_clientes['IdCltes']; ?></td>
        <td><?php echo $row_clientes['NomCltes']; ?></td>
        <td><?php echo $row_clientes['CuitCltes']; ?></td>
        <td><?php echo $row_clientes['TelmovilCltes']; ?></td>
        <td><?php echo $row_clientes['TelfijoCltes']; ?></td>
        <td><?php echo $row_clientes['EmailCltes']; ?></td>
        <td><?php echo $row_clientes['CategCltes']; ?></td>
        <td><?php echo $row_clientes['FealtaCltes']; ?></td>
      </tr>
      <?php } while ($row_clientes = mysql_fetch_assoc($clientes)); ?>
  </table>
  <p>
  <table class="paginacion">
    <tr>
      <td><?php if ($pageNum_clientes > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_clientes=%d%s", $currentPage, 0, $queryString_clientes); ?>">Primero</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_clientes > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_clientes=%d%s", $currentPage, max(0, $pageNum_clientes - 1), $queryString_clientes); ?>">Anterior</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_clientes < $totalPages_clientes) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_clientes=%d%s", $currentPage, min($totalPages_clientes, $pageNum_clientes + 1), $queryString_clientes); ?>">Siguiente</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_clientes < $totalPages_clientes) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_clientes=%d%s", $currentPage, $totalPages_clientes, $queryString_clientes); ?>">&Uacute;ltimo</a>
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
mysql_free_result($clientes);
?>
