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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO lugares (Nomlug, Dirlug, Tellug, Emaillug, Cuitlug) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Nomlug'], "text"),
                       GetSQLValueString($_POST['Dirlug'], "text"),
                       GetSQLValueString($_POST['Tellug'], "int"),
                       GetSQLValueString($_POST['Emaillug'], "text"),
                       GetSQLValueString($_POST['Cuitlug'], "int"));

  mysql_select_db($database_cnn, $cnn);
  $Result1 = mysql_query($insertSQL, $cnn) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_lugares = 1;
$pageNum_lugares = 0;
if (isset($_GET['pageNum_lugares'])) {
  $pageNum_lugares = $_GET['pageNum_lugares'];
}
$startRow_lugares = $pageNum_lugares * $maxRows_lugares;

mysql_select_db($database_cnn, $cnn);
$query_lugares = "SELECT * FROM lugares";
$query_limit_lugares = sprintf("%s LIMIT %d, %d", $query_lugares, $startRow_lugares, $maxRows_lugares);
$lugares = mysql_query($query_limit_lugares, $cnn) or die(mysql_error());
$row_lugares = mysql_fetch_assoc($lugares);

if (isset($_GET['totalRows_lugares'])) {
  $totalRows_lugares = $_GET['totalRows_lugares'];
} else {
  $all_lugares = mysql_query($query_lugares);
  $totalRows_lugares = mysql_num_rows($all_lugares);
}
$totalPages_lugares = ceil($totalRows_lugares/$maxRows_lugares)-1;
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
  	<h1>Alta lugar  </h1>
  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">IdLug</td>
      <td class="tablapriincipal">Nomlug</td>
      <td class="tablapriincipal">Dirlug</td>
      <td class="tablapriincipal">Tellug</td>
      <td class="tablapriincipal">Emaillug</td>
      <td class="tablapriincipal">Cuitlug</td>
      <td class="tablapriincipal">Felugar</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_lugares['IdLug']; ?></td>
        <td><?php echo $row_lugares['Nomlug']; ?></td>
        <td><?php echo $row_lugares['Dirlug']; ?></td>
        <td><?php echo $row_lugares['Tellug']; ?></td>
        <td><?php echo $row_lugares['Emaillug']; ?></td>
        <td><?php echo $row_lugares['Cuitlug']; ?></td>
        <td><?php echo $row_lugares['Felugar']; ?></td>
      </tr>
      <?php } while ($row_lugares = mysql_fetch_assoc($lugares)); ?>
  </table>
  <p>&nbsp;</p>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" class="element">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nombre:</td>
      <td><input type="text" name="Nomlug" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Direcci�n:</td>
      <td><input type="text" name="Dirlug" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Telefono:</td>
      <td><input type="text" name="Tellug" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email:</td>
      <td><input type="text" name="Emaillug" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuit:</td>
      <td><input type="text" name="Cuitlug" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insertar registro" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
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
mysql_free_result($lugares);
?>
