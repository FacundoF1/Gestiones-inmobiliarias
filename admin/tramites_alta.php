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
	include("C:\AppServ\www\Tesis\admin\selectramite\lugares.php");
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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="duplicado.php";
  $loginUsername = $_POST['NunTrm'];
  $LoginRS__query = sprintf("SELECT NunTrm FROM tramites WHERE NunTrm=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_cnn, $cnn);
  $LoginRS=mysql_query($LoginRS__query, $cnn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tramites (Nomlug, TpoTrm, PrdTrm, Objeto, NomCltes, DniCltes, ConvenioTrm, TasaTrm, ColegioTrm, ImpTrm, FePresen, NunTrm, NomUsu, IdUsuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Nomlug'], "text"),
                       GetSQLValueString($_POST['TpoTrm'], "text"),
                       GetSQLValueString($_POST['PrdTrm'], "text"),
                       GetSQLValueString($_POST['Objeto'], "text"),
                       GetSQLValueString($_POST['NomCltes'], "text"),
                       GetSQLValueString($_POST['DniCltes'], "text"),
                       GetSQLValueString($_POST['ConvenioTrm'], "float"),
                       GetSQLValueString($_POST['TasaTrm'], "float"),
                       GetSQLValueString($_POST['ColegioTrm'], "float"),
                       GetSQLValueString($_POST['ImpTrm'], "float"),
                       GetSQLValueString($_POST['FePresen'], "date"),
                       GetSQLValueString($_POST['NunTrm'], "text"),
                       GetSQLValueString($_POST['NomUsu'], "text"),
					   $_SESSION['IdUsu']
					   );
                       

  mysql_select_db($database_cnn, $cnn);
  $Result1 = mysql_query($insertSQL, $cnn) or die(mysql_error());
}

mysql_select_db($database_cnn, $cnn);
$query_clientes = "SELECT * FROM clientes";
$clientes = mysql_query($query_clientes, $cnn) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

mysql_select_db($database_cnn, $cnn);
$query_tramites = "SELECT * FROM tramites ORDER BY IdTrm DESC";
$tramites = mysql_query($query_tramites, $cnn) or die(mysql_error());
$row_tramites = mysql_fetch_assoc($tramites);
$totalRows_tramites = mysql_num_rows($tramites);

mysql_select_db($database_cnn, $cnn);
$query_lugares = "SELECT * FROM lugares";
$lugares = mysql_query($query_lugares, $cnn) or die(mysql_error());
$row_lugares = mysql_fetch_assoc($lugares);
$totalRows_lugares = mysql_num_rows($lugares);

mysql_select_db($database_cnn, $cnn);
$query_tipotrm = "SELECT * FROM tipotrm";
$tipotrm = mysql_query($query_tipotrm, $cnn) or die(mysql_error());
$row_tipotrm = mysql_fetch_assoc($tipotrm);
$totalRows_tipotrm = mysql_num_rows($tipotrm);

mysql_select_db($database_cnn, $cnn);
$query_prioridad = "SELECT * FROM prioridadtrm";
$prioridad = mysql_query($query_prioridad, $cnn) or die(mysql_error());
$row_prioridad = mysql_fetch_assoc($prioridad);
$totalRows_prioridad = mysql_num_rows($prioridad);

mysql_select_db($database_cnn, $cnn);
$query_usuarios = "SELECT * FROM usuarios where NomCat='Escribana'";
$usuarios = mysql_query($query_usuarios, $cnn) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$totalRows_usuarios = mysql_num_rows($usuarios);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/BaseAdmin.dwt" codeOutsideHTMLIsLocked="false" -->
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<meta charset="UTF-8">
<title>Administracion gestiones inmobiliarias</title>
<script language="JavaScript" src="../js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="../themes/base/jquery.ui.all.css" media="all" /> 
<script src="../js/jquery-1.4.2.js"></script>
<script src="../ui/jquery.ui.core.js"></script>
<script src="../ui/jquery.ui.widget.js"></script>
<script src="../ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="../ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../demos/images/calendar.gif">
<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			showOn: "button",
			firstDay: 1,
			buttonImage: "../demos/images/calendar.gif",
			buttonImageOnly: true
			});
		
	});
	</script>
    <script>
	$(function() {
		$( "#datepicker2" ).datepicker({
			showOn: "button",
			minDate: "1D",
			maxDate: "+2M",
			firstDay: 1,
			buttonImage: "../demos/images/calendar.gif",
			buttonImageOnly: true
		});
	});
</script>
<script>	
function calcular_total() {
	importe_total = 0
	$(".importe_linea").each(
		function(index, value) {
			importe_total = importe_total + eval($(this).val());
		}
	);
	$("#total").val(importe_total);
}
 
function nueva_linea() {
	$("#lineas").append('<input type="text0" class="importe_linea" value="0"/><br/>');
	$("#lineas").append('<input type="text1" class="importe_linea" value="0"/><br/>');
	$("#lineas").append('<input type="text2" class="importe_linea" value="0"/><br/>');
}
</script>
<script language="JavaScript" src="../js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script>
$(document).ready(function(){
    $( "#nombre" ).autocomplete({
        source: "completecliente/cnn_buscarcliente.php",
		
		});
		$("#nombre").focusout(function(){
        $.ajax({
            url:'completecliente/cnn_cliente.php',
            type:'POST',
            dataType:'json',
            data:{ nombre:$('#nombre').val()}
        }).done(function(respuesta){
            $("#dni").val(respuesta.dni);
        });
    });
});
</script>
<script>
		$(document).ready(function(){
			$("#Nomlug").change(function() {
				var pais = $(this).val();
				
				if(pais > 0)
				{
			        var datos = {
			            idPais : $(this).val()  
			        };

			        $.post("tipotrm.php", datos, function(ciudades) {
			        	
					  	var $comboCiudades = $("#TpoTrm");
		                $comboCiudades.empty();
		                $.each(ciudades, function(index, ciudad) {
		        			//
	                        $comboCiudades.append("<option>" + ciudad.nombre + "</option>");
		                });
					}, 'json');
				}
				else
				{
					var $comboCiudades = $("#TpoTrm");
	                $comboCiudades.empty();
					$comboCiudades.append("<option>Seleccione un Lugar</option>");
				}
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
  <h1> Tramites Ingresados...  </h1>
  <table width="100%" border="0" class="tablaingresos">
    <tr class="tablaingresos">
      <td width="11%" class="tablapriincipal">Tramite N&ordm;</td>
      <td width="17%" class="tablapriincipal">Fecha</td>
      <td width="13%" class="tablapriincipal">Tramite</td>
      <td width="12%" class="tablapriincipal">Prioridad</td>
      <td width="22%" class="tablapriincipal">Escribana/o</td>
      <td width="17%" class="tablapriincipal">Cliente</td>
      <td width="8%" class="tablapriincipal">Importe</td>
    </tr>
    <tr>
      <td><?php echo $row_tramites['NunTrm']; ?></td>
      <td><?php echo $row_tramites['FePresen']; ?></td>
      <td><?php echo $row_tramites['TpoTrm']; ?></td>
      <td><?php echo $row_tramites['PrdTrm']; ?></td>
      <td><?php echo $row_tramites['NomUsu']; ?></td>
      <td><?php echo $row_tramites['NomCltes']; ?></td>
      <td><?php echo "$ ",$row_tramites['ImpTrm']; ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table id="advert" class="element">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre Lugar:</td>
         <td>
			<select id="Nomlug" name="Nomlug">
				<option value="0">Seleccione un lugar</option>
				<?php
					$paises = obtenerTodosLosPaises();
					foreach ($paises as $pais) { 
						echo '<option value="'.$pais->id.'">'.utf8_encode($pais->nombre).'</option>';		
					}
				?>
			</select>
            </td>
   		</tr>
     	<tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre Tramite:</td>
        <td>
        <select id="TpoTrm" name="TpoTrm">
				<option value="0">Seleccione un Tramite</option>
		</select>
        </td>
       </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Prioridad Tramite:</td>
        <td><select name="PrdTrm">
          <?php 
do {  
?>
          <option value="<?php echo $row_prioridad['NomPrd']?>" ><?php echo $row_prioridad['NomPrd']?></option>
          <?php
} while ($row_prioridad = mysql_fetch_assoc($prioridad));
?>
        </select></td>
      </tr>
      <tr> </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Objeto:</td>
        <td><input type="text" name="Objeto" value="" size="32" required placeholder="Objeto"/></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre y Apellido cliente:</td>
        <td><input id="nombre" type="text" name="NomCltes" value="" size="32"></input></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Dni:</td>
        <td><input id="dni" type="text" name="DniCltes" value="" size="32" /></td>
      </tr>
      <div id="lineas"><tr valign="baseline">
        <td nowrap="nowrap" align="right">Convenio $:</td>
        <td><input type="text0" class="importe_linea" name="ConvenioTrm" value="0" size="32"/></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Tasa $:</td>
        <td><input type="text1" class="importe_linea" name="TasaTrm" value="0" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Colegio $:</td>
        <td><input type="text2" class="importe_linea" name="ColegioTrm" value="0" size="32"/></td>
      </tr></div>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Importe Total:</td>
        <td><input id="total" type="text" name="ImpTrm" value="0" size="32" /><td><input type="button" value="Calcular" onClick="calcular_total()"/> </td></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Fecha de Presentación:</td>
        <td><input type="text" id="datepicker" name="FePresen" value="" size="32" /><td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Numero Tramite:</td>
        <td><input type="text" name="NunTrm" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Escribano:</td>
        <td><label for="select"></label>
          <select name="NomUsu" id="select">
            <?php
do {  
?>
            <option value="<?php echo $row_usuarios['NomUsu']?>"><?php echo $row_usuarios['NomUsu']?></option>
            <?php
} while ($row_usuarios = mysql_fetch_assoc($usuarios));
  $rows = mysql_num_rows($usuarios);
  if($rows > 0) {
      mysql_data_seek($usuarios, 0);
	  $row_usuarios = mysql_fetch_assoc($usuarios);
  }
?>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insertar registro" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
</form>
  <p>&nbsp;</p>
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
<?php
mysql_free_result($clientes);

mysql_free_result($tramites);

mysql_free_result($lugares);

mysql_free_result($tipotrm);

mysql_free_result($prioridad);

mysql_free_result($usuarios);
?>
