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
$CPP = 10; //Views per page
$titulo_barra = "Resumen de noticias";
$filtro_fecha_hoy = " and n.fecha_nota = CURRENT_DATE() ";
$dia_h = date("D");
//echo $dia_h."<br>";
switch($dia_h)
{
case "Sun": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-2 ";
case "Sat": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-1 ";
case "Mon": $filtro_dia_hoy = " and ( n.fecha_nota = CURRENT_DATE()-2 or n.fecha_nota = CURRENT_DATE()-1 or n.fecha_nota = CURRENT_DATE() )  ";
}




  
//echo "USUARIO: ".$usuario;
//Ya verifique el login
//Verifico suscripciones
$suscripcion = 0; //Si es 0 no esta suscripto o por fecha o por estado, si es 1 si
	$db = mysql_connect("localhost","w1750056","vagi67naFU");
				mysql_select_db("w1750056_pmacca_databig",$db);
 
 
 
		
		$tema = $_GET['tema'];
		
		
	 if (strlen($tema)==0)
	 $tema = 'industria';
	 
	 
$rsx = mysql_query("select max(fecha_nota) from tnotas where codigo_tema = '".$tema."'");
while ($ss = mysql_fetch_array($rsx))	
$filtro_fecha_hoy = " and n.fecha_nota = '".$ss[0]."' ";

if ($hojaestilo=="default") { $hojaestilo = ""; } 
else { 
$hojaestilo="";
//$hojaestilo = "<link rel='stylesheet' href='estilos/".$hojaestilo."' type='text/css'>";
}

//Paginacion
$getema = "&tema=".$tema;


//Listar noticias de entre begin y end

  if (($_GET['BuscarFecha']=="Buscar")  )
	{
		
		$query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg,  n.region
				from tnotas n, tautores f where 
				n.codigo_tema = '".$tema."'   and f.id = n.fuente  and n.estado = 1 and n.fecha_nota = '".$_GET['fanio']."/".$_GET['fmes']."/".$_GET['fdia']."'  order by  n.region asc, n.id desc";
			
			$titulo_barra = "Notas del ".$_GET['fdia']."/".$_GET['fmes']."/".$_GET['fanio'];
				}else{
		if (($_GET['btnBuscar']=="Buscar")  )
		{
			$query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg,  n.region
				from tnotas n, tautores f where 
				n.codigo_tema = '".$tema."'   and f.id = n.fuente and n.estado = 1  and ( n.titulo like '%".$_GET['search']."%' or n.copete like '%".$_GET['search']."%' )  order by n.region asc, n.id desc";
			$titulo_barra = "Resultados de la búsqueda";

		//echo $query;
		}else
		{  //$filtro_fecha_hoy = "";
					//echo "DEMO";
					/*break;
				}*/
			$query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region
				from tnotas n, tautores f where 
				n.codigo_tema = '".$tema."'   and f.id = n.fuente and n.estado = 1 ".$filtro_fecha_hoy." order by  n.region asc, n.id desc";
			
			};
		}
	$con = mysql_query($query,$db);
	
//echo $query;
	$i = 0;
	$inicio = 0;
	$actual = 1;
	if ($_GET['page']!="")	$actual = $_GET['page'];
	$inicio = ($actual*$CPP)-$CPP;
	$fin = $inicio+$CPP;
//	echo $inicio." - ".$fin;
$tit5=0;$tit1=0;$tit2=0;$tit3=0;$tit4=0;
	while ($rs = mysql_fetch_array($con))
	{
		if (($i>=$inicio)&&($i<$fin))
		{
			$titulo = $rs[3];
			$volanta = $rs[4];
			$resumen = nl2br($rs[5]);
			$fecha = $rs[0]." de ".$lista_meses[$rs[1]-1]." de ".$rs[2];
			$autor = $rs[7];
			$idn = $rs[8];	
			$tamimg = $rs[9];
			
			if (($tit1==0)&&($rs[10]=="Argentina"))
			{
			$listado_notas.=
			"<tr>
    <td height='20' bgcolor='#006666' class='TITULO_BLANCO'>&nbsp;&nbsp;En Argentina</td>
  </tr>";
  $tit1=1;
			}
			if (($tit2==0)&&($rs[10]=="Bolivia"))
			{
			$listado_notas.=
			"<tr>
    <td height='20' bgcolor='#006666' class='TITULO_BLANCO'>&nbsp;&nbsp;En Bolivia</td>
  </tr>";
  $tit2=1;
			}
			if (($tit3==0)&&($rs[10]=="Bolivia"))
			{
			$listado_notas.=
			"<tr>
    <td height='20' bgcolor='#006666' class='TITULO_BLANCO'>&nbsp;&nbsp;En Chile</td>
  </tr>";
  $tit3=1;
			}
			if (($tit4==0)&&($rs[10]=="Paraguay"))
			{
			$listado_notas.=
			"<tr>
    <td height='20' bgcolor='#006666' class='TITULO_BLANCO'>&nbsp;&nbsp;En Paraguay</td>
  </tr>";
  $tit4=1;
			}
			if (($tit5==0)&&($rs[10]=="Uruguay"))
			{
			$listado_notas.=
			"<tr>
    <td height='20' bgcolor='#006666' class='TITULO_BLANCO'>&nbsp;&nbsp;En Uruguay</td>
  </tr>";
  $tit5=1;
			}
			$listado_notas.=
			"<tr><td class='volanta'>
			
			".$volanta."</td></tr>
			<tr><td bgcolor='#FFFFFF' class='titulo'><a href='vernota.php?id=".$idn."' 				class='titulo2'>".$titulo."</a></td></tr>
			<tr><td height='1' valign='middle' bgcolor='#cccccc'></td></tr>
			<tr><td class='texto'>".$resumen."</td></tr>
			<tr><td align='right' valign='middle'><a href='vernota.php?id=".$idn."' class='vernota'>Ver nota completa</a></td></tr>
			<tr><td height='20' align='right' valign='middle' bgcolor='#ffffff'><span class='fondo_gris'><span class='fuente'>&nbsp;Fuente: <span class='autor'>".$autor."</span></span><span class='fecha'>, ".$fecha."&nbsp;</span></span></td></tr>
			<tr><td height='2' align='right' valign='bottom' bgcolor='#333333'></td></tr>";
		};
		$i++;
	}
	$tipo_susc = $i;
	mysql_close($db);
	$db = mysql_connect("localhost","w1750056","vagi67naFU");
				mysql_select_db("w1750056_pmacca_databig",$db);
	
	$cons = mysql_query("select count(id),DATE_FORMAT(fecha_nota,'%e'),DATE_FORMAT(fecha_nota,'%c'),DATE_FORMAT(fecha_nota,'%Y') from tnotas where codigo_tema = '".$tema."' group by fecha_nota order by fecha_nota desc LIMIT 0,10");
	$blog="<tr><td bgcolor='#006666' height='20' class='TITULO_BLANCO'>&nbsp;: &Uacute;ltimas noticias</td><tr>";
	
	
	while($d=mysql_fetch_array($cons))
	{
	
	 $blog.="<tr><td><a  class='LINKS_MENU2' href='?BuscarFecha=Buscar&page=1&tema=".$tema."&fdia=".$d[1]."&fmes=".$d[2]."&fanio=".$d[3]."'>".$d[1]." de ".$lista_meses[$d[2]-1]." (".$d[0].") </a></td></tr>";
	
	
	//page=1&tema=cityscape&fdia=19&fmes=9&fanio=2008&BuscarFecha=Buscar
	
	}
	
	mysql_close();
	if ($i<1) $listado_notas.= "<tr><td class='texto'>No se han publicado noticias de acuerdo al criterio solicitado.<br><br><a href='javascript:history.go(-1);' class='vernota'>:: Volver</a></td></tr>";
	
	$total = intval($i/$CPP);
	
	$resto = 0;
	if (($i%$CPP)>0) $resto = 1;
	$total+=$resto;
	if ($total ==0) $total =1;
	$pie_pagina = "Página ".$actual." de ".$total." ";
	$pie_pagina.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| ";
	if ($_GET['btnBuscar']=="Buscar")
	{

	$CADENA_BUSCAR = "&btnBuscar=Buscar&search=".$_GET['search']."&p=p";
		//if ($_GET['search']=="") $CADENA_BUSCAR = "&btnBuscar=Buscar&search=&nbsp;&p=op";
	
	}
	if ($_GET['BuscarFecha']=="Buscar")
	{
	$CADENA_BUSCAR.= "&BuscarFecha=Buscar&fdia=".$_GET['fdia']."&fmes=".$_GET['fmes']."&fanio=".$_GET['fanio'];
	}
	for ($i=1;$i<=$total;$i++)
	{
		if ($i==$actual)
			$pie_pagina.="<a class='lnk_pagina_actual' href='?page=".$i.$getema.$CADENA_BUSCAR."'>".$i."</a> ";
		else
			$pie_pagina.="<a class='texto' href='?page=".$i.$getema.$CADENA_BUSCAR."'>".$i."</a> ";
	
		
	}



	
	
function fecha_diff( $data1, $data2 )
 {

 // 86400 seg = 60 [seg/1_minuto] * 60 [1_minuto / 1_hora]* 24 [1_hora]

        $segundos  = strtotime($data2)-strtotime($data1);
        $dias      = intval($segundos/86400);
        $sl_retorna = $dias;
        return $sl_retorna;
 }	
 
 if (strtolower($user)=="cityscape") $logos = "<img src='logos/cityscape.jpg' />";
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
<form name="login" method="GET" id="login" action="index.php">
<table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td height="24">
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
		<param name='movie' value='/logo2.swf'>
		<param name='quality' value='high'>
		<embed src='/logo2.swf' quality='high' pluginspage='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' width='680' height='180'></embed>
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
    <td height="20" bgcolor="#006666" class="TITULO_BLANCO">&nbsp;: <?php echo $titulo_barra; ?></td>
  </tr>
	<?php echo $listado_notas; ?>
	

	<tr>
		<td height="20" align="right" valign="middle" bgcolor="#eeeeee" class="fecha"><?php echo $pie_pagina; ?>&nbsp;</td>
	</tr>
</table>

	</td><td width="0" bgcolor="#eeeeee"></td>
    <td width="200" align="left" valign="top"><table width="200" style="border:0px;" border="0" cellspacing="6" cellpadding="0"><? echo $logos; ?>
	  <tr>
    <td height="20" bgcolor="#006666" class="TITULO_BLANCO">&nbsp;: B&uacute;squeda de notas</td>
  </tr>
<tr><td align="left" valign="middle" > <a  href=<?php if ($tema!="")  echo "?search=&btnBuscar=Buscar&tema=".$tema;  else  echo "?search=&btnBuscar=Buscar";?> class="LINKS_MENU2">
	&raquo; Ver todas las notas</a></td>
</tr>
	<tr>
		<td bgcolor="#FFFFFF" class="texto_verde"><input onClick="limpiar('txtbusqueda');" class="form_textbox_180" type="text" value="Escriba el texto a buscar" id="txtbusqueda" name="search"/><input type="hidden" value="<?php echo $actual; ?>" name="page"/><input type="hidden" value="<?php echo $tema; ?>" name="tema"/></td>
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

		
			<?php for ($a=1;$a<=31;$a++) 
			{
			if ($_GET['fdia'])
			{
				if ($_GET['fdia']==$a)
					echo "<option selected='selected'>".$a."</option>"; 
				else
					echo "<option  value=".$a.">".$a."</option>";
			}else
			{
			if ($a==date("d"))
				echo "<option selected='selected'>".$a."</option>";
			else
				echo "<option>".$a."</option>";
			}
			}
			?>
		</select>
&nbsp;<select name="fmes" id="fmes"  class="form_textbox_60">
		<?php 
		for ($a=1;$a<=12;$a++)
		{
			if ($_GET['fmes'])
			{
				if ($_GET['fmes']==$a)
					echo "<option value=".$a." selected='selected'>".$lista_meses[$a-1]."</option>"; 
				else
					echo "<option  value=".$a.">".$lista_meses[$a-1]."</option>";
			}
			else
			{
				if ($a==date("m")) 
					echo "<option value=".$a." selected='selected'>".$lista_meses[$a-1]."</option>"; 
				else 
					echo "<option  value=".$a.">".$lista_meses[$a-1]."</option>";
			}
		 }
		 ?>
		
		</select>
	&nbsp;<select name="fanio" id="fanio"  class="form_4numeros">
			<?php echo "<option>2008</option>"; echo "<option selected='selected'>2009</option>"; ?>
		</select></td>
	</tr>
		<tr>
		<td align="right" class="texto" valign="middle"><input type="submit" value="Buscar" id="BuscarFecha" name="BuscarFecha" class="BOTON_NARANJA"/></td>
	</tr>
		
		
		<tr>
		<td height="1" valign="middle" bgcolor="#999999"></td>
	</tr>
 
 <? echo $blog; ?>
 
     </table></td>
  </tr>
 
</table>

</td></tr></table>
</form>
</body>
</html>
