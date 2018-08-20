<?php 
@require_once '../env.php';


function texto($a) {
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

if ($_GET['id']=="") header("Location: ".$_SERVER['HTTP_REFERER']);
$CPP = 10; //Views per page
$lista_meses = array(
        0=>"Enero",
        1=>"Febrero",
		2=>"Marzo",
        3=>"Abril",
		4=>"Mayo",
        5=>"Junio",
		6=>"Julio",
        7=>"Agosto",
		8=>"Septiembre",
        9=>"Octubre",
		10=>"Noviembre",
        11=>"Diciembre");

 

$suscripcion = 0;
	$db = mysql_connect("localhost","w1750056","vagi67naFU");
				mysql_select_db("w1750056_pmacca_databig",$db);
	//echo $user;
	 
	 if ($hojaestilo=="default") { $hojaestilo = ""; } 
else { 
$hojaestilo="";
//$hojaestilo = "<link rel='stylesheet' href='estilos/".$hojaestilo."' type='text/css'>";
}

//Paginacion



//Listar noticias de entre begin y end


$db = mysql_connect("localhost","w1750056","vagi67naFU");
				mysql_select_db("w1750056_pmacca_databig",$db);
	$query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.codigo_tema, n.tamimg
				from tnotas n, tautores f where 
				 f.id = n.fuente and n.id = ".$_GET['id']." and n.estado = 1";
	//echo $query;
	$con = mysql_query($query,$db);
	$cant_not = 0;
	while ($rs = mysql_fetch_array($con))
	{
		//echo "pepe";
		$cant_not++;
		$titulo = $rs[3];
		$volanta = $rs[4];
		$resumen = nl2br($rs[6]);
		$tema = $rs[9];
		$fecha = $rs[0]." de ".$lista_meses[$rs[1]-1]." de ".$rs[2];
		//$texto = nl2br($rs[6]);
		$autor = $rs[7];
		$idn = $rs[8];
		$tamimg = $rs[10];
		$copete = $rs[5];
			$code_img="";
			for ($ppp=1;$ppp<7;$ppp++)
			{
			if (file_exists("imgnotas/".$idn."_".$ppp.".jpg")) 
			{
				 $code_img .= "<img src='/imgnotas/".$idn."_".$ppp.".jpg'  /><br>";
			} 
			}	
			 
		$listado_notas.=
		"<tr><td class='volanta'>".$volanta."</td></tr>
		<tr><td bgcolor='#FFFFFF' class='titulo'><a href='vernota.php?id=".$idn."' class='titulo'>".$titulo."</a></td></tr><tr><td class='copete'>".$copete."</td></tr>
		<tr><td height='1' valign='middle' bgcolor='#cccccc'></td></tr>
		<tr><td class='texto'>".$resumen."</td></tr>";
		//"<tr><td align='right' valign='middle'><a href='vernota.php?id=".$idn."' class='vernota'>Ver nota completa</a></td></tr>
			 
			
		$listado_notas.="<tr><td height='20' align='right' valign='middle' bgcolor='#ffffff'><span class='fondo_gris'><span class='fuente'>&nbsp;Fuente: <span class='rojo'>".$autor."</span></span><span class='fecha'>, ".$fecha."&nbsp;</span></span></td></tr><tr><td>".$code_img."</td></tr>
		<tr><td height='2' align='right' valign='bottom' bgcolor='#333333'></td></tr>";
		 	}
	mysql_close($db);
	if ($cant_not<1) $listado_notas.= "<tr><td class='texto'>No hay notas con el criterio buscado.<br><br><a href='javascript:history.go(-1);' class='vernota'>:: Volver</a></td></tr>";
	
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
<link rel="stylesheet" href="../style_new.css" type="text/css">
<?php echo $hojaestilo; ?>
<script src="../funciones.js" type="text/javascript"></script>
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body bgcolor="#ffffff">
<form name="login" method="get" id="login" action="index.php" >
<table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
	<table width="680" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td  colspan="3" align="center" valign="top"><?php 
	if ($tema=="pra")
	{
	echo "<a href='http://www.fertilidadperu.com' target='blank'><img src='logos/pranor.jpg' width='100%' border='0'/></a>";
	}
	else
	{
	echo "<script type='text/javascript'>
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','680','height','180','src','logo2','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','logo2' );  
</script><noscript><object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0' width='680' height='180'>
		<param name='movie' value='logo2.swf'>
		<param name='quality' value='high'>
		<embed src='logo2.swf' quality='high' pluginspage='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' width='680' height='180'></embed>
    	</object></noscript>"; 
	}	
		?></td>
  </tr>

	<tr>
    <td height="6" colspan="3" align="center" ></td>
	</tr>
	<tr>
    <td height="20" colspan="3" align="center" valign="middle" bgcolor="#006666"><table width="680" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="120"></td><td width="100"   align="center" ><a href="../index.php" target="_self" class="LINKS_MENU">
					<div id="hom">Home</div>
				</a></td>
				<td width="4" class="LINKS_MENU">|</td><td width="100"  align="center" ><a class="LINKS_MENU" href="index.php">
					<div id="reg4">Clipping</div>
				</a></td><td width="4" class="LINKS_MENU">|</td>
				<td width="100"   align="center" ><a class="LINKS_MENU"  href="../contact_p.php<?php echo "?cl=$tema"; ?>">
					<div id="reg4">Contacto</div>
				</a></td><td width="4" class="LINKS_MENU">|</td>
				 
			
				
				<td width="100" align="center" ><a  class="LINKS_MENU" href="../index.php?logout=yes">
					<div id="con">Salir</div>
				</a></td><td width="120"></td>
			</table>
</td></tr>
  <tr><td colspan="3"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>
	
	<tr>
    <td width="480"  align="center" valign="top">
	<table width="460" style="border:solid 1px; border-color:#fcfcfc" border="0" cellspacing="6" cellpadding="0">
	 <tr>
    <td height="30" valign="middle" class="texto"><a  class="vernota" href="javascript:history.go(-1);">:: Volver  </a> </td>
  </tr>
	<?php echo $listado_notas; ?>
	
	
</table>

	</td><td width="0" bgcolor="#eeeeee"></td>
    <td width="200" align="left" valign="top"><table width="200" style="border:0px;" border="0" cellspacing="6" cellpadding="0">
	 <tr>
    <td height="20" bgcolor="#006666" class="TITULO_BLANCO">&nbsp;: B&uacute;squeda de Notas</td>
  </tr>

	<tr>
		<td bgcolor="#FFFFFF" class="texto_verde"><input onClick="limpiar('txtbusqueda');" class="form_textbox_180" type="text" value="Escriba el texto a buscar" id="txtbusqueda" name="search"/><input type="hidden" value=1 name="page"/><input type="hidden" value="<?php echo $tema; ?>" name="tema"/></td>
	</tr>

		<tr>
		<td align="right" valign="middle"><input type="submit" value="Buscar" id="btnBuscar" name="btnBuscar" class="BOTON_NARANJA"/></td>
	</tr>
		<tr>
		<td height="1" valign="middle" bgcolor="#999999"></td>
	</tr>
	<tr>
    <td height="20" bgcolor="#006666" class="TITULO_BLANCO">&nbsp;: Notas anteriores</td>
  </tr>

	<tr>
		<td bgcolor="#FFFFFF" class="texto">Seleccione la fecha de las notas: </td>
	</tr>
<tr>
		<td bgcolor="#FFFFFF" class="texto"><select name="fdia" id="fdia"  class="form_2numeros">

		
			<?php 
			for ($a=1;$a<=31;$a++) 
			{
			if ($a==date("d"))
				echo "<option selected='selected'>".$a."</option>";
			else
				echo "<option>".$a."</option>";
			}
			?>
		</select>
&nbsp;<select name="fmes" id="fmes"  class="form_textbox_60">
		<?php 
		for ($a=1;$a<=12;$a++)
		{
			if ($a==date("m")) 
			echo "<option value=".$a." selected='selected'>".$lista_meses[$a-1]."</option>"; 
			else 
			echo "<option  value=".$a.">".$lista_meses[$a-1]."</option>";
		 }
		 ?>
		
		</select>
	&nbsp;<select name="fanio" id="fanio"  class="form_4numeros">
			<?php echo "<option>2008</option>"; for ($a=1900;$a<=2010;$a++) echo "<option>".$a."</option>";?>
		</select></td>
	</tr>
		<tr>
		<td align="right" valign="middle"><input type="submit" value="Buscar" id="BuscarFecha" name="BuscarFecha" class="BOTON_NARANJA"/></td>
	</tr>
		
		
		<tr>
		<td height="1" valign="middle" bgcolor="#999999"></td>
	</tr>



	
</table></td>
  </tr>
 
     </table></td>
  </tr>
 
</table>
</form>
</body>
</html>
