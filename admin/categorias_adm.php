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

$maxRows_categoria = 3;
$pageNum_categoria = 0;
if (isset($_GET['pageNum_categoria'])) {
  $pageNum_categoria = $_GET['pageNum_categoria'];
}
$startRow_categoria = $pageNum_categoria * $maxRows_categoria;

mysql_select_db($database_cnn, $cnn);
$query_categoria = "SELECT * FROM categorias ORDER BY IdCategoria DESC";
$query_limit_categoria = sprintf("%s LIMIT %d, %d", $query_categoria, $startRow_categoria, $maxRows_categoria);
$categoria = mysql_query($query_limit_categoria, $cnn) or die(mysql_error());
$row_categoria = mysql_fetch_assoc($categoria);

if (isset($_GET['totalRows_categoria'])) {
  $totalRows_categoria = $_GET['totalRows_categoria'];
} else {
  $all_categoria = mysql_query($query_categoria);
  $totalRows_categoria = mysql_num_rows($all_categoria);
}
$totalPages_categoria = ceil($totalRows_categoria/$maxRows_categoria)-1;

$queryString_categoria = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_categoria") == false && 
        stristr($param, "totalRows_categoria") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_categoria = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_categoria = sprintf("&totalRows_categoria=%d%s", $totalRows_categoria, $queryString_categoria);
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
  	<p><h1>Bienvenido a Administración de categorias...</h1>
  <div class="barramenu">
   <ul class="menu">
     <li><a href="index.php">Inicio</a></li>
     <li><a href="categorias_alta.php">Alta categorias</a></li>
   </ul>
  </div>
  <p align="left">&nbsp;</p>


  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">IdCat</td>
      <td class="tablapriincipal">NomCat</td>
      <td class="tablapriincipal">NomRol</td>
      <td class="tablapriincipal">FeRol</td>
      <td class="tablapriincipal">Edición</td>
    </tr>
    <?php do { ?>
      <tr>
      	<td><?php echo $row_categoria['IdCategoria']; ?></td>
        <td><?php echo $row_categoria['NomCat']; ?></td>
        <td><?php echo $row_categoria['NomRol']; ?></td>
        <td><?php echo $row_categoria['FeRol']; ?></td>
        <td><a href="categorias_edit.php?IdCategoria=<?php echo $row_categoria['IdCategoria']; ?>">Editar</a></td>
      </tr>
      <?php } while ($row_categoria = mysql_fetch_assoc($categoria)); ?>
  </table>
  <p>&nbsp;  
  <table class="paginacion">
    <tr>
      <td><?php if ($pageNum_categoria > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_categoria=%d%s", $currentPage, 0, $queryString_categoria); ?>"><img src="First.gif" /> Primero</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_categoria > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_categoria=%d%s", $currentPage, max(0, $pageNum_categoria - 1), $queryString_categoria); ?>"><img src="Previous.gif" /> Anterior</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_categoria < $totalPages_categoria) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_categoria=%d%s", $currentPage, min($totalPages_categoria, $pageNum_categoria + 1), $queryString_categoria); ?>">Siguiente <img src="Next.gif" /></a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_categoria < $totalPages_categoria) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_categoria=%d%s", $currentPage, $totalPages_categoria, $queryString_categoria); ?>">Ultimo <img src="Last.gif" /></a>
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
mysql_free_result($categoria);
?>
