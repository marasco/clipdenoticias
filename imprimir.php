<?php
@require_once 'env.php';

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

if (isset($_COOKIE['usuarios']))
	{
		if (strlen($_COOKIE['usuarios'])>0) $user = $_COOKIE['usuarios'];
	} else
	{
		if ((strlen($_COOKIE['iadmin'])>0)) $user = "admin";
	}
if (strlen($user)==0) { header("location: index.php?msg=nologin"); }

$suscripcion = 0;
  $db = mysql_connect("192.168.0.192","mysql_root","fran21");
                                mysql_select_db("clipping",$db);
	//echo $user;
	if ($user!="admin")
	{
		$query = "select S.codigo_tema 'd1', S.estilo 'd2', S.fecha_creacion 'd3' from tsuscripciones S where S.usuario = '".$user."'";
		$con = mysql_query($query,$db);
		while ($rs = mysql_fetch_array($con))
		{
			$suscripcion = 1;
			$tema = $rs[0];
			$hojaestilo = $rs[1];
		}
		mysql_close($db);
		if ($suscripcion == 0) {  header("location: index.php?msg=nosusc");  }
	///	echo $suscripcion."AA";
	}
	else
	{
		//	echo $user."AA";
	}
	/*if ($tema == "") {  header("location: index.php?msg=notema");  }*/
if ($hojaestilo=="default") { $hojaestilo = ""; } 
else { 
$hojaestilo="";
//$hojaestilo = "<link rel='stylesheet' href='estilos/".$hojaestilo."' type='text/css'>";
}

//Paginacion



//Listar noticias de entre begin y end


  $db = mysql_connect("192.168.0.192","mysql_root","fran21");
  mysql_select_db("clipping",$db);
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
			if (file_exists("img/notas/nota_".$idn.".jpg")){
				if ($tamimg ==0)
				{
					$size_img = " width = '80' height = '80' ";
					$size_div = " width:80px; height:80px; ";
				}else
				{
					$size_div = " width:448px; height:200px; ";
					$size_img = " width = '448' height = '200' ";
				}
			  	$code_img = "<div style='position:relative; float:right; background:#eeeeee; padding-bottom:2px;padding-top:2px;padding-left:2px; padding-right:2px;  left: 0; top: 0; ".$size_div." overflow: hidden;'><img src='img/notas/nota_".$idn.".jpg' ".$size_img." /></div>";
				
			
			}else{
			  $code_img = "";
			} 
			
		$listado_notas.=
		"<tr><td class='volanta'>".$volanta."</td></tr>
		<tr><td bgcolor='#FFFFFF' class='titulo'><a href='vernota.php?id=".$idn."' class='titulo'>".$titulo."</a></td></tr>
		<tr><td height='1' valign='middle' bgcolor='#cccccc'></td></tr>
		<tr><td class='texto'>".$code_img.$resumen."</td></tr>";
		//"<tr><td align='right' valign='middle'><a href='vernota.php?id=".$idn."' class='vernota'>Ver nota completa</a></td></tr>
			$code_img = "";
			for ($x=1;$x<6;$x++)
			{
			if (file_exists("img/notas/nota_".$idn."_".$x.".jpg"))
				{
			 	 	$code_img .="<tr><td class='texto' align='left'><img src='img/notas/nota_".$idn."_".$x.".jpg' 
					 width = '448'  /></td></tr>";
					 
				} 
			}
		$listado_notas.="<tr><td height='20' align='right' valign='middle' bgcolor='#ffffff'><span class='fondo_gris'><span class='fuente'>&nbsp;Fuente: <span class='rojo'>".$autor."</span></span><span class='fecha'>, ".$fecha."&nbsp;</span></span></td></tr>
		<tr><td height='2' align='right' valign='bottom' bgcolor='#333333'></td></tr>";
		if ($code_img!="") $listado_notas.="<tr><td valign='middle' class='fuente'>Imágenes de la nota</td></tr><tr><td height=1><hr size='1' color='#ececec' noshade='noshade' /></td></tr>";
		//$listado_notas.=$code_img;
	}
	mysql_close($db);
	if ($cant_not<1) $listado_notas.= "<tr><td class='texto'>No hay notas con el criterio buscado.<br><br><a href='javascript:history.go(-1);' class='vernota'>:: Volver</a></td></tr>";
	
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
<link rel="stylesheet" href="style.css" type="text/css">
<?php echo $hojaestilo; ?>
<script src="funciones.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body bgcolor="#ffffff">
<form name="login" method="get" id="login" action="principal.php" >
<table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
	<table width="660" border="0" align="center" cellpadding="2" cellspacing="0">
 

	<tr>
    <td width="660"  align="center" valign="top">
	<table width="660" style="border:solid 1px; border-color:#fcfcfc" border="0" cellspacing="6" cellpadding="0">
	
	<tr>
    <td height="20" class="texto"><a  class="vernota" href="javascript:history.go(-1);">:: Volver</a> &nbsp;/&nbsp; <a href="javascript:print();" class="vernota">&nbsp;Imprimir </a></td>
  </tr> <tr>
    <td height="20"><?php 
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
  </tr> <tr><td colspan="3"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>
	
	 
	<?php echo $listado_notas; ?>
</table>	</td>
	</tr>
     </table></td>
  </tr>
 
</table>
</form>
</body>
</html>
