<?php require_once('Connections/cnn.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['correo'])) {
  $loginUsername=$_POST['correo'];
  $password=$_POST['pass'];
  $MM_fldUserAuthorization = "NomCat";
  $MM_redirectLoginSuccess = "front/incio.php";
  $MM_redirectLoginFailed = "error.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_cnn, $cnn);
  	
  $LoginRS__query=sprintf("SELECT EmailUsu, PassUsu, NomCat FROM usuarios WHERE EmailUsu=%s AND PassUsu=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $cnn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'NomCat');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<script type="text/javascript">
document.createElement("main");
document.createElement("header");
document.createElement("footer");
document.createElement("section");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("figure");
</script>
<!--[if lt IE 9]>
<script src="dist/html5shiv.js"></script>
<![endif]-->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Gestiones inmobiliarias</title>
<link rel="stylesheet" href="css/ajustable.css">
<script>
function show_login() 
{
    document.getElementById('form').style.display='block';
}
</script>;
</head>
<body>
<header>
 
  <div class="logo">
  <figure class="logo"><img class="logo" src="imagen/portada.jpg" /></figure>
  <label class="logo">Gestiones Inmobiliarias</label>
  </div>
  
	<article class="nav">
	<nav>
    	<ul>
    	<li class="activar"><a href="index.php"><p class="texto">Inicio</p><span><img class="icon" src="imagen/ecqlipse 2 - system white/HOME.png"></span></a>
    	</li>
    	<li><a href="servicios.php"><p class="texto">Servicios</p><span><img class="icon" src="imagen/ecqlipse 2 - system white/SYNCAPP.png"></span></a>
    	</li>
    	<li><a href="noticias.php"><p class="texto">Noticias</p><span><img class="icon" src="imagen/ecqlipse 2 - system white/BOOK.png"></span></a>
    	</li>
    	<li><a href="informacion.php"><p class="texto">Información</p><span><img class="icon" src="imagen/ecqlipse 2 - system white/INFO.png"></span></a>
    	</li>
    	<li><a href="contacto.php"><p class="texto">Contacto</p><span><img class="icon" src="imagen/ecqlipse 2 - system white/MAIL.png"></span></a>
    	</li>
		</ul>
   	</nav>
	</article>

  <article class="login">
   	<a href="#" onClick="show_login(); return_false;" id="login"> Iniciar sesión <span><img src="imagen/ecqlipse 2 - system white/POWER - STANDBY.png"></span></a>
    
	<div id="form" style="display:none;">
	<section>
    <article class="contact">
     <form action="<?php echo $loginFormAction; ?>" method="POST" name="Login">
      <table>
      <tr>
      	<td><label>Usuario:</label></td>
       </tr>
      <tr>
      	<td><input type="email" title="*** Ingresar correo electronico ***" name="correo" placeholder=" Correo electronico " required="" >      </td>
      </tr>
      <tr>
      <td><label>Contraseña:</label></td>
      </tr>
      <td><input type="password" name="pass" title="*** Ingresar su clave personal ***"  placeholder=" Clave personal " required=""></td>
      </tr>
      <tr>
        <td><input class="envio" type="submit" value="Ingresar"></td>
      </tr>
    </table>
    </form>
    </article>
    </section>


	</div>
  </article>

</header>

<section class="centrado">

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
    </div>
<p align="center">Derechos Reservados Tesina Instituto San Jose i27.- &copy; 2014-2015</p>
</footer>

</body>
</html>