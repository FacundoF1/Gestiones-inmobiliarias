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
  $insertSQL = sprintf("INSERT INTO usuarios (NomUsu, ApeUsu, DniUsu, PassUsu, TelMovil, TelFijo, NomCat, EmailUsu) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['NomUsu'], "text"),
                       GetSQLValueString($_POST['ApeUsu'], "text"),
                       GetSQLValueString($_POST['DniUsu'], "text"),
                       GetSQLValueString($_POST['PassUsu'], "text"),
                       GetSQLValueString($_POST['TelMovil'], "text"),
                       GetSQLValueString($_POST['TelFijo'], "text"),
                       GetSQLValueString($_POST['NomCat'], "text"),
                       GetSQLValueString($_POST['EmailUsu'], "text"));

  mysql_select_db($database_cnn, $cnn);
  $Result1 = mysql_query($insertSQL, $cnn) or die(mysql_error());
}

$maxRows_usuarios = 1;
$pageNum_usuarios = 0;
if (isset($_GET['pageNum_usuarios'])) {
  $pageNum_usuarios = $_GET['pageNum_usuarios'];
}
$startRow_usuarios = $pageNum_usuarios * $maxRows_usuarios;

mysql_select_db($database_cnn, $cnn);
$query_usuarios = "SELECT * FROM usuarios ORDER BY IdUsuario DESC";
$query_limit_usuarios = sprintf("%s LIMIT %d, %d", $query_usuarios, $startRow_usuarios, $maxRows_usuarios);
$usuarios = mysql_query($query_limit_usuarios, $cnn) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);

if (isset($_GET['totalRows_usuarios'])) {
  $totalRows_usuarios = $_GET['totalRows_usuarios'];
} else {
  $all_usuarios = mysql_query($query_usuarios);
  $totalRows_usuarios = mysql_num_rows($all_usuarios);
}
$totalPages_usuarios = ceil($totalRows_usuarios/$maxRows_usuarios)-1;
?>
<?php 
mysql_select_db($database_cnn, $cnn);
$query_categorias ="SELECT * FROM categorias";
$categorias = mysql_query($query_categorias, $cnn) or die(mysql_error());
$row_categorias = mysql_fetch_assoc($categorias);
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
  	<h1>Alta usuario  </h1>
  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">IdUsuario</td>
      <td class="tablapriincipal">NomUsu</td>
      <td class="tablapriincipal">ApeUsu</td>
      <td class="tablapriincipal">DniUsu</td>
      <td class="tablapriincipal">TelMovil</td>
      <td class="tablapriincipal">TelFijo</td>
      <td class="tablapriincipal">NomCat</td>
      <td class="tablapriincipal">EmailUsu</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_usuarios['IdUsuario']; ?></td>
        <td><?php echo $row_usuarios['NomUsu']; ?></td>
        <td><?php echo $row_usuarios['ApeUsu']; ?></td>
        <td><?php echo $row_usuarios['DniUsu']; ?></td>
        <td><?php echo $row_usuarios['TelMovil']; ?></td>
        <td><?php echo $row_usuarios['TelFijo']; ?></td>
        <td><?php echo $row_usuarios['NomCat']; ?></td>
        <td><?php echo $row_usuarios['EmailUsu']; ?></td>
      </tr>
      <?php } while ($row_usuarios = mysql_fetch_assoc($usuarios)); ?>
  </table>
  <p>&nbsp;</p>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" class="element">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre:</td>
        <td><input type="text" name="NomUsu" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Apellido:</td>
        <td><input type="text" name="ApeUsu" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Dni:</td>
        <td><input type="text" name="DniUsu" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Contraseña:</td>
        <td><input type="password" name="PassUsu" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Telefono Movil:</td>
        <td><input type="text" name="TelMovil" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Telefono Fijo:</td>
        <td><input type="text" name="TelFijo" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre Categoria:</td>
        <td><select name="NomCat">
          <?php 
do {  
?>
          <option value="<?php echo $row_categorias['NomCat']?>" ><?php echo $row_categorias['NomCat']?></option>
          <?php
} while ($row_categorias = mysql_fetch_assoc($categorias));
?>
        </select></td>
      </tr>
      <tr> </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Email:</td>
        <td><input type="text" name="EmailUsu" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insertar registro" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
  </form>
  <p>&nbsp;</p>
<br />
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
mysql_free_result($usuarios);

mysql_free_result($categorais);
?>