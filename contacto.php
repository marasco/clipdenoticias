<?php
function texto($a)
{
$a = str_replace("<","&lt;",$a);
$a = str_replace(">","&gt;",$a);
$a = str_replace("'","&quot;",$a);
$a = str_replace(chr(34),"&quot;",$a);

return $a;
}
function logout($nombre_cook)
	{
		setcookie($nombre_cook,"",0,"/");
		setcookie("iadmin","",0,"/");
		header("Location: index.php?ok");
	}

function aleatoria($cant){
	for ($j=1;$j<=$cant;$j++)
		$nombre.=rand(0,9);
	return $nombre;

};
function validar_str($string)
{
$string = str_replace("'",chr(34),$string);
$string = str_replace(";",",",$string);
$string = str_replace("<","(",$string);
$string = str_replace(">",")",$string);
return $string;

};

$titulo = "Contacto";
 
 $mensaje_top =   "<tr>
  	<td class='LINKS_MENU'>&nbsp;* Datos necesarios</td>
  </tr>";

$mensaje = "Para poder recibir una demostración de nuestro servicio de clipping, complete con todos sus datos el formulario que se muestra a continuación:<br><br>Directora: <span class='fuente'>Candelaria Marasco Quiroga</span><br>E.mail: <span class='texto_verde'>cmarasco@clipdenoticias.com</span>";

$addform = "";
if ($_GET['as']==1) 
{
$addform = "?as=1";
$titulo = "Solicite una demo";
$mensaje = "Solicitando la demostración del funcionamiento del servicio, usted puede ingresar al sistema durante 2 días como si estuviese abonado al mismo. Para poder recibir este beneficio le solicitamos el envío de este formulario.";
}
if ($_GET['sub']=="soporte") 
{
$addform = "?sub=soporte";
$titulo = "Soporte Técnico";
$mensaje = "Complete el formulario si tiene un problema así nosotros podremos solucionarlo.";
}

	if ($_POST['btnAceptar']=="Enviar")
	{
		$campo_1 =  $_POST['campo_1'];
		$campo_2 =  $_POST['campo_2'];
		$campo_3 =  $_POST['campo_3'];
		$campo_4 =  $_POST['campo_4'];
		$campo_5 =  $_POST['campo_5'];
		$campo_6 =  $_POST['campo_6'];
		$campo_0 = $_POST['campo_0'];


		if ((($campo_1 == "")||($campo_2 == "")||($campo_3 == "")||($campo_4 == "")||($campo_5 == "")||($campo_6 == ""))==1) { $mensaje_top = "  <tr><td bgcolor='#FEEBC7' class='lnk_pagina_actual style1'>&nbsp;* Faltan datos necesarios</td></tr>"; $SCREEN = "&nbsp;"; }

		if ($SCREEN=="")
		{
		$rcpt = "cmarasco@clipdenoticias.com";
		if ($_GET['sub']=="soporte")
		{
			$rcpt = "soporte@clipdenoticias.com";
		}
		$sender = "noreply@clipdenoticias.com";
	$host = "mail.clipdenoticias.com"; $port = 25;
	$conexion = fsockopen($host, $port);
	fputs($conexion,"HELO fran\r\n");
    fputs($conexion,"MAIL FROM: ".$sender."\r\n");
    fputs($conexion,"RCPT TO: ".$rcpt."\r\n");
    fputs($conexion,"DATA\r\n");
	$mandar = "From: 'Contacto' <noreply@clipdenoticias.com>\r\n";
	if ($_GET['sub']=="soporte")
		{
		$mandar = "From: 'Soporte Técnico' <soporte@clipdenoticias.com>\r\n";
		}
	fputs($conexion,$mandar);

	if ($titulo!="Contacto") $campo_5 = "SOLICITUD DE DEMO";
	if ($_GET['sub']=="soporte") $campo_5 = "Help";
	fputs($conexion,"Subject: Asunto: ".$campo_5."\r\n\r\n");
    $mandar="";
	$mandar = "Nombre: ".$campo_1."\nEmpresa: ".$campo_0."\nTelefono: ".$campo_2."\nEmail: ".$campo_3."\nPais: ".$campo_4."\nMensaje: \n\t".$campo_6."\n\nclipdenoticias.com\r\n";
	if ($_GET['sub']=="soporte")
	{
	$mandar = "Username: ".$_COOKIE['usuarios']."\n\nDescripcion del problema: \n\t".$campo_6."\n\nclipdenoticias.com\r\n";
	}
    ini_set(sendmail_from,"soporte@clipdenoticias.com");
mail("cmarasco@clipdenoticias.com",$campo_5,$mandar,"");
	fputs($conexion,$mandar);


	fputs($conexion,"\r\n.\r\n");
					$campo_0 = "";
					$campo_1 = "";
					$campo_2 = "";
					$campo_3 = "";
					$campo_4 = "";
					$campo_5 = "";
					$campo_6 = "";
					$mensaje_top =  "<tr>
  	<td class='LINKS_MENU'>&nbsp;Se ha enviado el mensaje correctamente.</td>
  </tr>";;
					//header("location: contacto.php?SCREEN=Gracias%20por%20");

		}

	}
	?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
<link rel="stylesheet" href="style_new.css" type="text/css">
<script language="javascript" src="funciones.js"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<style type="text/css">
<!--
.style1 {
	color: #CC0000
}
-->
</style>
</head>

<body bgcolor="#ffffff">
<form name="login" method="POST" id="login" action="contacto.php" enctype="multipart/form-data">
<table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
	<table width="680" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td  colspan="2" align="center" valign="top"><script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','680','height','180','src','/logo2','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','/logo2' ); //end AC code
	</script>
    	<noscript>
    	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="680" height="180">
			<param name="movie" value="/logo2.swf">
			
			<embed src="/logo2.swf" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="680" height="180"></embed>
		</object>
    	</noscript>    	</td>
  </tr>

	<tr>
    <td height="6" colspan="2" align="center" ></td>
	</tr>
	<!--<tr>
    	<td height="20" colspan="2" align="center" valign="middle"><table width="300" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="105" align="center" ><a href="index.php"  onMouseOver="javascript:document.getElementById('hom').style.background='#f5f5f5';" onMouseOut="javascript:document.getElementById('hom').style.background='#ffffff';" target="_self" class="LINKS_MENU">
					<div id="hom">Home</div>
				</a></td>
				<td width="105" align="center" ><a class="LINKS_MENU"  onMouseOver="javascript:document.getElementById('reg').style.background='#f5f5f5';" onMouseOut="javascript:document.getElementById('reg').style.background='#ffffff';"  href="contacto.php?as=1">
					<div id="reg">Solicitar Demo</div>
				</a></td>
			
				
				<td width="105" align="center" ><a  onMouseOver="javascript:document.getElementById('con').style.background='#f5f5f5';" onMouseOut="javascript:document.getElementById('con').style.background='#ffffff';"  class="LINKS_MENU" href="contacto.php">
					<div id="con">Contacto</div>
				</a></td>
			</table></td>
	</tr>-->
	<tr>
    <td height="20" colspan="3" align="center" valign="middle" bgcolor="#006666"><table width="552" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="120"></td><td width="100"   align="center" ><a href="index.php" target="_self" class="LINKS_MENU">
					<div id="hom">Home</div>
				</a></td>
				<td width="4" class="LINKS_MENU">|</td><td width="100"  align="center" ><a class="LINKS_MENU" href="contacto.php?as=1">
					<div id="reg4">Solicitar Demo</div>
				</a></td><td width="4" class="LINKS_MENU">|</td>
				<td width="100"   align="center" ><a class="LINKS_MENU"  href="contacto.php">
					<div id="reg4">Contacto</div>
				</a></td><td width="120"></td></tr>
			</table>
</td></tr>
  <tr><td colspan="2"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>
	
	<tr>
  
    <td width="680" align="left" valign="top"><table width="680" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td bgcolor="#006666" class="TITULO_BLANCO"><?php echo $titulo;?></td>
  </tr>
 
    <tr>
  	<td ><div class="texto_portada"><?php echo $mensaje; ?></div></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <?php echo $mensaje_top; ?>

 
  <tr>
    <td><table width="676" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td width="209" height="30" align="right" valign="middle" class="texto_verde">Nombre y Apellido:</td>
        <td width="8" height="30">&nbsp;</td>
        <td width="453" height="30" class="LINKS_MENU"><input  name="campo_1" type="text" class="form_textbox_200" id="campo_1" maxlength="50" value="<?php echo $campo_1; ?>"> 
          * </td>     
      </tr>
	     <tr>
        <td width="209" height="30" align="right" valign="middle" class="texto_verde">Empresa :</td>
        <td width="8" height="30">&nbsp;</td>
        <td width="453" height="30" class="LINKS_MENU"><input  name="campo_0" type="text" class="form_textbox_200" id="campo_0" maxlength="50" value="<?php echo $campo_0; ?>">           </td>     
      </tr>
	  <tr>
        <td width="209" height="30" align="right" valign="middle" class="texto_verde">Telefono de Contacto :</td>
        <td width="8" height="30">&nbsp;</td>
        <td height="30" class="LINKS_MENU"><input   name="campo_2" type="text" class="form_textbox_200" id="campo_2" maxlength="50"  value="<?php echo $campo_2; ?>"> 
        	*          </td>     
      </tr>
	  
	  <tr>
        <td width="209" height="30" align="right" valign="middle" class="texto_verde">E-mail :</td>
        <td width="8" height="30">&nbsp;</td>
        <td height="30" class="LINKS_MENU"><input name="campo_3" type="text"  class="form_textbox_200" id="campo_3" maxlength="100"  value="<?php echo $campo_3; ?>"> 
          * </td>     
      </tr>

	  
	  
	  <tr>
        <td width="209" height="30" align="right" valign="middle" class="texto_verde">Pais :</td>
        <td width="8" height="30">&nbsp;</td>
        <td height="30" class="LINKS_MENU"><select   name="campo_4" class="form_textbox_200" id="campo_4" >
			<option >Argentina</option>
			<option >Bolivia</option>
			<option >Brasil</option>
			<option >Chile</option>
			<option >Ecuador</option>
			<option >España</option>
			<option >México</option>
			<option >Paraguay</option>
			<option >Venezuela</option>
			<option >Uruguay</option>
			<option >Otro</option>
		</select></td>     
      </tr>
		  <tr>
        <td width="209" height="30" align="right" valign="middle" class="texto_verde">Asunto  :</td>
        <td width="8" height="30">&nbsp;</td>
        <td height="30" class="LINKS_MENU"><input name="campo_5" type="text" class="form_textbox_200" id="campo_5" value="<?php if ($campo_5!="SOLICITUD DE DEMO") echo $campo_5; ?>"   > 
          * </td>     
      </tr>
	  <tr><td colspan="2" height="8"></td></tr>
 <tr>
        <td width="209" height="30" align="right" valign="top" class="texto_verde">Mensaje  :</td>
        <td width="8" height="30">&nbsp;</td>
        <td height="30" class="LINKS_MENU"><input name="campo_6" type="text" class="form_textarea8" id="campo_6" value="<?php echo $campo_6; ?>"   > 
          * </td>      
      </tr>
<tr valign="middle"><td height="30"></td>
<td height="30"></td>
<td height="30" align="left"><input name="btnAceptar" type="submit" class="BOTON_NARANJA" id="btnAceptar" value="Enviar"></td>
</tr>
<tr><td colspan="3" class="texto_portada"><p><span class="fuente">Contacto</span>: Candelaria Marasco Quiroga<br>
      <span class="fuente">E-Mail:</span> cmarasco@clipdenoticias.com<br>
      <span class="fuente">Teléfono:</span> 11-5825-3363 | Desde el exterior: 54911-5825-3363<br>
  <br>
  Con mucho gusto atenderemos sus dudas, consultas y sugerencias. Muchas gracias!.<br>
  <span class="fuente"><br>
</span><span class="fuente"><span class="verde_logo">Clip</span> de Noticias</span></p>
    </td>
</tr>
<tr valign="middle"><td height="20"></td>
<td height="20"></td>
<td height="20" align="left" class="texto_verde"><?php echo $SCREEN; ?></td>
</tr>

    </table></td>
  </tr>
</table></td>
  </tr>
     </table></td>
  </tr>
 
</table>
</form>
</body>
</html>
