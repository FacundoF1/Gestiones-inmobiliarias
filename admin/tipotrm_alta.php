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
  $insertSQL = sprintf("INSERT INTO tipotrm (Nomtrm, Nomlug, IdLug) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['Nomtrm'], "text"),
                       GetSQLValueString($_POST['Nomlug'], "text"),
                       GetSQLValueString($_POST['IdLug'], "int"));

  mysql_select_db($database_cnn, $cnn);
  $Result1 = mysql_query($insertSQL, $cnn) or die(mysql_error());
}
mysql_select_db($database_cnn, $cnn);
$query_lugares = "SELECT * FROM lugares";
$lugares = mysql_query($query_lugares, $cnn) or die(mysql_error());
$row_lugares = mysql_fetch_assoc($lugares);
$totalRows_lugares = mysql_num_rows($lugares);

$maxRows_tipotrm = 1;
$pageNum_tipotrm = 0;
if (isset($_GET['pageNum_tipotrm'])) {
  $pageNum_tipotrm = $_GET['pageNum_tipotrm'];
}
$startRow_tipotrm = $pageNum_tipotrm * $maxRows_tipotrm;

mysql_select_db($database_cnn, $cnn);
$query_tipotrm = "SELECT * FROM tipotrm ORDER BY IdTrm DESC";
$query_limit_tipotrm = sprintf("%s LIMIT %d, %d", $query_tipotrm, $startRow_tipotrm, $maxRows_tipotrm);
$tipotrm = mysql_query($query_limit_tipotrm, $cnn) or die(mysql_error());
$row_tipotrm = mysql_fetch_assoc($tipotrm);

if (isset($_GET['totalRows_tipotrm'])) {
  $totalRows_tipotrm = $_GET['totalRows_tipotrm'];
} else {
  $all_tipotrm = mysql_query($query_tipotrm);
  $totalRows_tipotrm = mysql_num_rows($all_tipotrm);
}
$totalPages_tipotrm = ceil($totalRows_tipotrm/$maxRows_tipotrm)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/BaseAdmin.dwt" codeOutsideHTMLIsLocked="false" -->
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Administracion gestiones inmobiliarias</title>
<script language="JavaScript" src="../js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="../themes/base/jquery.ui.all.css" media="all" /> 
<script>
$(document).ready(function(){
	$( "#Nomlug" ).autocomplete({
        source: "completelugar/cnn_buscarlugar.php",
	});
    $("#Nomlug").focusout(function(){
        $.ajax({
            url:'completelugar/cnn_lugar.php',
            type:'POST',
            dataType:'json',
            data:{ Nomlug:$('#Nomlug').val()}
        }).done(function(respuesta){
            $("#IdLug").val(respuesta.IdLug);
        });
    });
});
</script>
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
  <h1>Alta de Tipo  de tramites...  </h1>
  <table class="tablaingresos">
    <tr>
      <td class="tablapriincipal">IdTrm</td>
      <td class="tablapriincipal">Nomtrm</td>
      <td class="tablapriincipal">Nomlug</td>
      <td class="tablapriincipal">Numero Identificador</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_tipotrm['IdTrm']; ?></td>
        <td><?php echo $row_tipotrm['Nomtrm']; ?></td>
        <td><?php echo $row_tipotrm['Nomlug']; ?></td>
        <td><?php echo $row_tipotrm['IdLug']; ?></td>
      </tr>
      <?php } while ($row_tipotrm = mysql_fetch_assoc($tipotrm)); ?>
  </table>
  <p>&nbsp;</p>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" class="element">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre de tramite:</td>
        <td><input type="text" name="Nomtrm" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre de lugar:</td>
        <td><input id="Nomlug" type="text" name="Nomlug" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Numero identificador</td>
        <td><input id="IdLug" type="text" name="IdLug" value="" size="32" /></td>
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

mysql_free_result($tipotrm);
?>
