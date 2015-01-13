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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/BaseAdmin.dwt" codeOutsideHTMLIsLocked="false" -->
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Administracion gestiones inmobiliarias</title>
<script language="javascript" src="../js/jquery-1.5.1.min.js"></script>
<link type="text/javascript" href="../demos/images/calendar.gif">
<link type="text/css" rel="stylesheet" href="../themes/base/jquery.ui.all.css" media="all" /> 
<script src="../ui/jquery.ui.core.js"></script>
<script src="../ui/jquery.ui.widget.js"></script>
<script src="../ui/jquery.ui.datepicker.js"></script>
<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			showOn: "button",
			buttonImage: "../demos/images/calendar.gif",
			buttonImageOnly: true
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
  <p><h1>Tramite.</h1></p>


<?php $numero=$_post['numero'];?>

  <table width="50%" align="center" class="element">

  <FORM METHOD="POST" ACTION="editramite.php">
    <td>Nombre cliente</td>
	<td><?php echo $_POST['cliente']?></td>
    <tr> 
   <td>Dni</td>
	<td><?php echo $_POST['dni']?></td>
   <tr>
    <td>Nombre lugar</td>
	<td><?php echo $_POST['lugar']?></td>
   <tr>
	<td>Tipo de tramite</td>
	<td><?php echo $_POST['tipo']?></td>
   <tr> 
	<td>Prioridad del tramite</td>
	<td><?php echo $_POST['prioridad']?></td>
   <tr>
	<td>Objeto</td>
	<td><?php echo $_POST['objeto']?></td>
   <tr>
    <td>Fecha de presentación</td>
    <td><?php echo $_POST['presentacion']?></td>
   <tr>
    <td>Numero de tramite</td>
	<td><input type="text" name="numero" value="<?php echo $_POST['numero']?>" />
   <tr>
    <td>Fecha tramite entregada</td>
    <td><input type="text" name="FeEntrega" id="datepicker" value="" size="32"></td>
   <tr>
   <tr>
    <td>Entregado</td>
    <td><form id="form1" name="form1" method="post" action="">
      <input type="radio" name="radio" id="entrega" value="entrega" />
      <label for="entrega"></label>
    </form></td>
   <tr>
    <tr>
	<td></td>
	<td><input type="SUBMIT" value="Actualizar registro"></input></td>
 
 </table>
 </FORM>
  <p align="center">&nbsp;</p>
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