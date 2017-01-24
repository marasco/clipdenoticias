<?php




include("funciones.php");

if ($_GET['logout']=="yes")
{
		setcookie("usuarios","",0,"/");
		setcookie("iadmin","",0,"/");
		header("Location: index.php?ok");
}

$mostrar = "";

if (!isset($_COOKIE['usuarios']))
{
$menu = "<table width='200' border='0' align='center' cellpadding='2' cellspacing='0' style='border:solid 1px; border-color:#006666; '><tr bgcolor='#006666'><td colspan='2' bgcolor='#006666' class='TITULO_BLANCO'> : Ingreso de clientes</td></tr><tr><td colspan='2' height='10'></td></tr><tr><td width='90' align='right' valign='middle' class='texto_verde'>usuario :</td><td width='90'><input name='txt1'   type='text' class='datos_textbox' id='txt1' maxlength='50'></td></tr><tr><td width='90' align='right' valign='middle' class='texto_verde'>contrase&ntilde;a : </td><td width='90'><input name='txt2'   type='password' class='datos_textbox' id='txt2' maxlength='50'></td></tr><tr><td>&nbsp;</td><td width='90' height='30' align='left' valign='top'><input name='btn1' type='submit' class='BOTON_NARANJA' id='btn1' value='Ingresar'></td></tr></table>";

if ($_COOKIE['iadmin']!="")
{

$menu = "<table width='200' border='0' align='center' cellpadding='2' cellspacing='0' style='border:solid 1px; border-color: #cccccc; '><tr bgcolor='#006666'>
		<td colspan='1' bgcolor='#006666' class='TITULO_BLANCO'> : Men&uacute; de administrador</td>
	</tr><tr><td colspan='1' height='4'></td>
	</tr>
		<tr><td colspan='1' height='1'></td>
	</tr>
	<tr valign='middle'  class='TITULO_BLANCO'>
		<td style='background-color:#FF6600;' id='menu_1' align='right' onMouseOver=chgColor('menu_1','#FF9900'); onMouseOut=chgColor('menu_1','#FF6600');><a href='admin/mysql.php' class='TITULO_BLANCO'><div >Visualizar DB <strong>&nbsp;&nbsp;&raquo;</strong> </div></a> </td></tr><tr><td colspan='1' height='1'></td>
	</tr><tr valign='middle'  class='TITULO_BLANCO'>
		<td style='background-color:#FF6600;' id='menu_2' align='right' onMouseOver=chgColor('menu_2','#FF9900'); onMouseOut=chgColor('menu_2','#FF6600');><a href='admin/upload.php' class='TITULO_BLANCO'><div >Cargar <strong>&nbsp;&nbsp;&raquo;</strong> </div></a> </td></tr><tr><td colspan='1' height='1'></td>
	</tr><tr valign='middle'  class='TITULO_BLANCO'>
		<td style='background-color:#FF6600;' id='menu_4' align='right' onMouseOver=chgColor('menu_4','#FF9900'); onMouseOut=chgColor('menu_4','#FF6600');><a href='index.php?logout=yes' class='TITULO_BLANCO'><div >Salir <strong>&nbsp;&nbsp;&raquo;</strong> </div></a> </td></tr>
		</table>";

}

if ($_POST["btn1"]=="Ingresar")
{

	if (($_POST["txt1"]!="") && ($_POST["txt2"]!=""))
	{
		
				$us = $_POST["txt1"];
				$co = $_POST["txt2"];
				$db = mysql_connect("localhost","w1750056","vagi67naFU");
				mysql_select_db("w1750056_pmacca_databig",$db);
				$query = "select S.usuario from tsuscripciones S where S.contrasena = '".$co."'";
				mysql_query($query,$db);
				$login_ok = 0;
				
				if (mysql_affected_rows($db)>0) $login_ok = 1;
				if ($login_ok==1)
					{
        					setcookie("usuarios",$us,time()+3600*24*60,"/");
							header("location: principal.php");
					} else 
					{
						if (($us =="candela")&&($co="tuyosiempre"))
						{
						setcookie("iadmin","candela",time()+3600*24*60,"/");
						header("location: admin/upload.php");
						}else{
						
						$MOSTRAR = "Nombre de usuario y contrase&ntilde;a incorrectos.";
						}
					}
				
			
	} else
	{
	$MOSTRAR = "Debes escribir tu nombre de usuario y contraseña.";
	}
}
		
		
		}
			else
			{
			$menu = "<table width='200' border='0' align='center' cellpadding='2' cellspacing='0' style='border:solid 1px; border-color: #cccccc; '><tr bgcolor='#006666'>
		<td colspan='1' bgcolor='#006666' class='TITULO_BLANCO'> : Men&uacute; de clientes</td>
	</tr><tr><td colspan='1' height='4'></td>
	</tr><tr valign='middle'  class='TITULO_BLANCO'>
		<td style='background-color:#FF6600;' id='menu_1' align='right' onMouseOver=chgColor('menu_1','#FF9900'); onMouseOut=chgColor('menu_1','#FF6600');><a href='principal.php' class='TITULO_BLANCO'><div >P&aacute;gina Principal <strong>&nbsp;&nbsp;&raquo;</strong> </div></a> </td></tr>
		<tr><td colspan='1' height='1'></td>
	</tr><tr valign='middle'  class='TITULO_BLANCO'>
		<td style='background-color:#FF6600;' id='menu_2' align='right' onMouseOver=chgColor('menu_2','#FF9900'); onMouseOut=chgColor('menu_2','#FF6600');><a href='contacto.php?sub=soporte' class='TITULO_BLANCO'><div >Soporte <strong>&nbsp;&nbsp;&raquo;</strong> </div></a> </td></tr><tr><td colspan='1' height='1'></td>
	</tr><tr valign='middle'  class='TITULO_BLANCO'>
		<td style='background-color:#FF6600;' id='menu_4' align='right' onMouseOver=chgColor('menu_4','#FF9900'); onMouseOut=chgColor('menu_4','#FF6600');><a href='index.php?logout=yes' class='TITULO_BLANCO'><div >Salir <strong>&nbsp;&nbsp;&raquo;</strong> </div></a> </td></tr>
		</table>";
			}
//	echo $menu;
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="funciones.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

</head>

<body bgcolor="#ffffff"><form name="login" method="POST" id="login" action="index.php">


<table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
	<table width="680" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td  colspan="2" align="center" valign="top"><script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','680','height','180','src','logo2','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','logo2' ); //end AC code
	</script>
    	<noscript>
    	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="680" height="180">
			<param name="movie" value="logo2.swf">
			<param name="quality" value="high">
			<embed src="logo2.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="680" height="180"></embed>
		</object>
    	</noscript>    	</td>
  </tr>

	<tr>
    <td height="6" colspan="2" align="center" ></td>
	</tr>
	<tr>
    	<td height="20" colspan="2" align="center" valign="middle">
		
		<?php 
		
		if ($_COOKIE['usuarios']!="")
{
echo "<table width='375' border='0' cellspacing='0' cellpadding='0'>
			<tr><td width='85' align='center' ><a href='index.php'  onMouseOver=chgColor('hom','#f5f5f5'); onMouseOut=chgColor('hom','#ffffff'); target='_self' class='LINKS_MENU'><div id='hom'>Home</div></a></td>
				<td width='105' align='center' ><a  onMouseOver=chgColor('con2','#f5f5f5'); onMouseOut=chgColor('con2','#ffffff');  class='LINKS_MENU' href='empresa.php'><div id='con2'>Quienes Somos</div></a></td><td width='105' align='center' ><a  onMouseOver=chgColor('cons','#f5f5f5'); onMouseOut=chgColor('cons','#ffffff');  class='LINKS_MENU' href='contacto.php'><div id='cons'>Contacto</div></a></td><td width='85' align='center' ><a  onMouseOver=chgColor('con3','#f5f5f5');  onMouseOut=chgColor('con3','#ffffff'); class='LINKS_MENU' href='index.php?logout=yes'><div id='con3'>Salir</div></a></td></table>";
		}else{
		if ($_COOKIE['iadmin']!="")
		{
		
		echo "<table width='300' border='0' cellspacing='0' cellpadding='0'>
			<tr><td width='105' align='center' ><a href='index.php'  onMouseOver=chgColor('hom','#f5f5f5');  onMouseOut=chgColor('hom','#ffffff'); target='_self' class='LINKS_MENU'><div id='hom'>Home</div></a></td>
				<td width='105' align='center' ><a class='LINKS_MENU'  onMouseOver=chgColor('reg','#f5f5f5');  onMouseOut=chgColor('reg','#ffffff');  href='admin/mysql.php'><div id='reg'>Visualizar DB</div></a></td><td width='105' align='center' ><a class='LINKS_MENU'  onMouseOver=chgColor('reg2','#f5f5f5');  onMouseOut=chgColor('reg2','#ffffff'); href='admin/upload.php'><div id='reg2'>Cargar</div></a></td>
				<td width='105' align='center' ><a  onMouseOver=chgColor('con','#f5f5f5');  onMouseOut=chgColor('con','#ffffff'); class='LINKS_MENU' href='index.php?logout=yes'><div id='con'>Salir</div></a></td></table>";
		
		}
		else
		{
	echo "<table width='400' border='0' cellspacing='0' cellpadding='0'><tr><td width='105' align='center' ><a href='index.php'   onMouseOver=chgColor('hom','#f5f5f5');  onMouseOut=chgColor('hom','#ffffff'); target='_self' class='LINKS_MENU'><div id='hom'>Home</div></a></td><td width='105' align='center' ><a class='LINKS_MENU'   onMouseOver=chgColor('reg','#f5f5f5');  onMouseOut=chgColor('reg','#ffffff');  href='contacto.php?as=1'><div id='reg'>Solicitar Demo</div></a></td><td width='105' align='center' ><a  onMouseOver=chgColor('con2','#f5f5f5'); onMouseOut=chgColor('con2','#ffffff');  class='LINKS_MENU' href='empresa.php'><div id='con2'>Quienes Somos</div></a></td><td width='105' align='center' ><a  onMouseOver=chgColor('con','#f5f5f5'); onMouseOut=chgColor('con','#ffffff');  class='LINKS_MENU' href='contacto.php'><div id='con'>Contacto</div></a></td></table>";
	}
}
		?>
		
			
			
			</td>
	</tr>
  <tr><td colspan="2"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>
	<tr><td valign="top"><?php echo $menu; ?></td>
	<td width="500" rowspan="4" align="left" valign="top"><table cellpadding="2" cellspacing="0" border="0">
	
		<tr bgcolor="#006666">
												<td colspan="2" bgcolor="#006666" class="TITULO_BLANCO"> : Descripci&oacute;n del servicio</td>
				</tr><tr><td align="right"><div  class='texto_portada'><strong> <span class="verde_logo">Clip</span></strong><span class="fuente"> de Noticias</span> es el servicio de clipping que busca y recopila todas las noticias periodísticas publicadas en los medios
de acuerdo al tema, marca, empresa, personalidad o institución de su interés.<br>
<br>Nuestro equipo de trabajo realiza diariamente una exhaustiva lectura y una completa selección de noticias publicadas en las ediciones on line de los medios gráficos, tanto de Argentina (Capital Federal e Interior) como del exterior del país.<br><br>El monitoreo de la información se realiza a primera hora de la mañana para que Usted pueda comenzar su jornada laboral bien informado acerca de los temas vinculados a su sector y con todo el conocimiento necesario para tomar decisiones.<br><br>Según su preferencia o necesidad, el servicio puede realizarse de manera diaria, semanal, quincenal o mensual.<br><br>Todos los recortes periodísticos seleccionados se alojan en nuestra página web y Usted accede a ellos a través de un nombre de usuario y un password. De esta manera, Usted puede ingresar al servicio desde cualquier computadora y en cualquier lugar del mundo.<br><br>
								Cada artículo periodístico contiene el medio y la fecha a la que pertenece y son alojados en nuestra página web en formato texto y con las imágenes correspondientes.
Nuestro informe digital le permite acceder rápidamente a toda la información publicada en los medios acerca del sector al que pertenece y además le permite ahorrar tiempo, papel y espacio físico.<br>
<br>
</div>
    <span class="dinerotransparente">www.<span class="verde_logo">clip</span>denoticias.com</span></td>
    </tr></table></td></tr>
	 <tr><td  height='3'  align='center' valign='top' class='texto_error'><?php echo $MOSTRAR; ?></td></tr>
	<tr>
    <td width="210"  align="center" valign="top" bgcolor="#fcfcfc"><iframe src="imagenes.htm" width="200" height="100" frameborder="0" scrolling="no"></iframe></td>
  </tr>
 
  <tr>
    <td width="210"   align="center" valign="top" bgcolor="#ffffff"><table width="200" border="0" align="center" cellpadding="2" cellspacing="0" style="border:solid 1px; border-color:#006666; ">
								
										
											<tr bgcolor="#006666">
												<td colspan="2" bgcolor="#006666" class="TITULO_BLANCO"> : Clipping retroactivo</td>
								</tr>
											<tr>
												<td height="10" colspan="2"><div class="texto_portada_2">La búsqueda de noticias histórica se realiza de acuerdo al tema solicitado en nuestro archivo de diarios de Argentina y del Exterior del País. Consulte medios y años disponibles.</div></td>
											</tr></table></td>
  </tr>
     </table></td>
  </tr>

</table><table width="700" border="0" align="center" cellpadding="2" cellspacing="0">
	<tr><td align="right"><span class="texto_error">Copyright 2008 - All rights reserved - <span class="dinerotransparente">www.<span class="verde_logo">clip</span>denoticias.com</span></span></td></tr></table>

</form></body>
</html>
