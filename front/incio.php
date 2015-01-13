<?php require_once('../Connections/cnn.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['IdUsu'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Gestor,Escribana";
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

$colname_usuarios = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuarios = $_SESSION['MM_Username'];
}
mysql_select_db($database_cnn, $cnn);
$query_usuarios = sprintf("SELECT * FROM usuarios WHERE EmailUsu = %s", GetSQLValueString($colname_usuarios, "text"));
$usuarios = mysql_query($query_usuarios, $cnn) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$_SESSION['IdUsu'] = $row_usuarios['IdUsuario'];
$totalRows_usuarios = mysql_num_rows($usuarios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Gestiones inmobiliarias</title>
<link rel="stylesheet" href="../css/incio.css" />
<link type="text/css" href="../front/css/style.css" rel="stylesheet" />
<script type="text/javascript" src="../front/js/jquery.js"></script>
<script type="text/javascript" language="../front/javascript" src="../front/js/funciones.js"></script>
<!– JQUERY –>
<!– FORMATO DE TABLAS –>
<link type="text/css" href="../front/css/jquery.dataTables.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../front/js/jquery.dataTables.js"></script>
<!– FORMATO DE TABLAS –>
</head>

<body>

<header>
 
  <div class="logo">
  <figure class="logo"><img class="logo" src="../imagen/portada.jpg" /></figure>
  <label class="logo">Gestiones Inmobiliarias</label>
  </div>
  
	<article class="nav">
	<nav>
    	<ul>
    	<li class="activar"><a href="incio.php"><p class="texto">Inicio</p><span><img class="icon" src="../imagen/ecqlipse 2 - system white/HOME.png"></span></a>
    	</li>
    	<li><a href="../admin/index.php"><p class="texto">Administración</p><span><img class="icon" src="../imagen/ecqlipse 2 - system white/SYNCAPP.png"></span></a>
    	</li>
    	<li><a href=""><p class="texto">Noticias</p><span><img class="icon" src="../imagen/ecqlipse 2 - system white/BOOK.png"></span></a>
    	</li>
    	<li><a href=""><p class="texto">Informacion</p><span><img class="icon" src="../imagen/ecqlipse 2 - system white/INFO.png"></span></a>
    	</li>
    	<li><a href=""><p class="texto">Contacto</p><span><img class="icon" src="../imagen/ecqlipse 2 - system white/MAIL.png"></span></a>
    	</li>
		</ul>
   	</nav>
	</article>
    
	<div class="sesion"><?php echo $row_usuarios['NomUsu']; ?><a class="sesion" href="<?php echo $logoutAction ?>"><img class="sesion" src=	"../imagen/ecqlipse 2 - system white/POWER - STANDBY.png"></a>
    </div>  
</header>

<section class="centrado">
<section>
  <article id="contenido"></article>
</section>
</section>

<footer>
	<div class="primero">
    <p> Sitios web </p><img src="imagen/@.png" /><hr>
    	<ul id="inicio"> 
      		<li><a href="http://www.corrientes.gov.ar/">Gobierno de Corrientes</a></li>
    		<li><a href="http://www.ciudaddecorrientes.gov.ar/">Municipalidad de Corrientes</a></li>
    		<li><a href="http://www.dgrcorrientes.gov.ar/rentascorrientes/consultarContenido.do">Dir. Gral. Rentas Corrientes</a></li>
    		<li><a href="http://www.catastro.corrientes.gov.ar/">Dir. Gral. Catastro Corrientes</a></li>
         </ul>
    </div>
    <div class="segundo">
    	<p> Redes </p><img src="imagen/Social.png" /><hr>
    	<ul id="inicio">
        	<li>Facebook</li>        
        	<li>Twiuter</li>
        	<li>Outlook</li>
        </ul>
    </div>
    <div class="tercero">
    	<p> Mensaje </p><img src="imagen/group.png" /><hr>
    	   <form method="post">
    		<table>
    		   <tr>
    			<td><input type="text" title="*** Ingrese su Nombre ***"  placeholder=" Nombre " required="" ></td>
    		   </tr>
    		   <tr>
    			<td><input type="email" title="*** Ingresar correo electronico ***"  placeholder=" Email " required="" ></td>
    	 	   </tr>
    		   <tr>
    			<td><textarea id="mensaje" title="*** Ingrese su  mensaje***"  placeholder=" Mensaje " required /></textarea></td>
    		   </tr>
    	  	   <tr>
    			<td><input id="enviar" type="submit" value="Enviar" ></td>
    		   </tr>
    		</table>
		   </form>
           <?php echo  $_SESSION['IdUsu']."`+++++++++" ;?> 
             </div>
<p align="center">Derechos Reservados Tesina Instituto San Jose i27.- &copy; 2014-2015</p>
</footer>

</body>
</html>
<?php
mysql_free_result($usuarios);
?>
