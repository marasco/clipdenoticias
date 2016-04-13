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
/*** Activa CIETT ***/
	$db = mysql_connect("192.168.0.192","mysql_root","fran21");
        mysql_select_db("clipping",$db);
	if (isset($_GET['CIETT']))
        {
		 mysql_query("update topciones set CIETT = ".intval($_GET['CIETT']),$db);
		if ($_GET['CIETT']=='1')
			header("Location: mysql.php?msg=CIETT Activado");
		else
			header("Location: mysql.php?msg=CIETT Desactivado");
        }
        if (isset($_GET['FAETT']))
        {
		mysql_query("update topciones set FAETT = ".intval($_GET['FAETT']),$db);
		if ($_GET['FAETT']=='1')
			header("Location: mysql.php?msg=FAETT Activado");
		else
			header("Location: mysql.php?msg=FAETT Desactivado");
        }
if (isset($_GET['CLETT']))
        {
		mysql_query("update topciones set CLETT = ".intval($_GET['CLETT']),$db);
		if ($_GET['CLETT']=='1')
			header("Location: mysql.php?msg=CLETT Activado");
		else
			header("Location: mysql.php?msg=CLETT Desactivado");
        }        
if (isset($_GET['WORK']))
        {
		mysql_query("update topciones set WORK = ".intval($_GET['WORK']),$db);
		if ($_GET['FAETT']=='1')
			header("Location: mysql.php?msg=WORK Activado");
		else
			header("Location: mysql.php?msg=WORK Desactivado");
        }
	$SCREEN = "";
        $admin="";

	if (($_GET['act']=="delete")&&($_GET['tabla']!="")&&($_GET['id']!="") )
	{
          /*  if (($_GET['tabla']=='tsuscripciones')) {
                if (!isset($_GET['confirm'])){
                    $SCREEN = "Esta seguro? <a href='?act=delete&tabla=tsuscripciones&confirm=1&id=".$_GET["id"]."'>Si</a>";
                } else{
                    mysql_query("delete from ".$_GET['tabla']." where id = ".$_GET['id'],$db);
                    $SCREEN = "Se ha eliminado el dato correctamente.";
                //}
            }else{*/
            mysql_query("delete from ".$_GET['tabla']." where id = ".$_GET['id'],$db);
            $SCREEN = "Se ha eliminado el dato correctamente.";//}
	}
	if (($_GET['act']=="chg_susc")&&($_GET['id']!=""))
	{
		mysql_query("update tsuscripciones set estado = ".$_GET['st']." where id = ".$_GET['id'],$db);
		$SCREEN = "Se ha cambiado el estado correctamente.";
	}
	$beginN = 0;
	if (intval($_GET['bg'])>0)
		$beginN =intval($_GET['bg']);
	if ($beginN>0)
		$anterior = "<a class='texto_verde' href='".$HTTP_SERVER_VARS['PHP_SELF']."?bg=".intval($beginN-100)."'>Anterior</a>";
		$anterior.=" / <a class='texto_verde' href='".$HTTP_SERVER_VARS['PHP_SELF']."?bg=".intval($beginN*1+100)."'>Siguiente</a>";	
	$query = "select id,codigo_tema,titulo from tnotas order by id desc limit ".$beginN.",100";
	
	$i = 0;
		$con = mysql_query($query,$db);
	while ($rs = mysql_fetch_array($con))
	{
	
	if ((($i+2)%2)==0) $color = '#FFFFFF'; else $color = '#eeeeee';
	$lista_notas.="<tr bgcolor='".$color."' class='texto_verde'><td width='20' >".$rs[0]."</td><td width='170' >".$rs[1]."</td><td width='421'>".$rs[2]."</td><td width='21' ></td><td width='18' ><a href='mysql.php?act=delete&tabla=tnotas&id=".$rs[0]."' class='link_action'><img src='/delete.jpg' border='0'/></a></td></tr>";
	$i+=1; 
	}
	mysql_close($db);
	
	/////Cargadas las notas
	
  $db = mysql_connect("192.168.0.192","mysql_root","fran21");
                                mysql_select_db("clipping",$db);
	$query = "select id,codigo_tema,usuario,contrasena,tipo,estado from tsuscripciones order by id desc";
	$i = 0;
		$con = mysql_query($query,$db);
	while ($rs = mysql_fetch_array($con))
	{
	
		if ((($i+2)%2)==0) $color = '#FFFFFF'; else $color = '#eeeeee';
		$stsusc = $rs[5];
		if ($stsusc ==1) { $stsusc = "Activo <a href='mysql.php?act=chg_susc&st=0&id=".$rs[0]."' class='LINKS_MENU'>Cambiar</a>"; }else { $stsusc = "Inactivo <a href='mysql.php?act=chg_susc&st=1&id=".$rs[0]."' class='LINKS_MENU'>Cambiar</a>"; }
	
	$lista_susc.="<tr bgcolor='".$color."' class='texto_verde'><td width='20' >".$rs[0]."</td><td width='150' >".$rs[1]."</td></td><td width='151' >".$rs[2]."</td><td width='100'>".$rs[3]."</td><td width='100'>".$rs[4]."</td><td width='121'>".$stsusc."</td><td width='18' ><a href='mysql.php?act=delete&tabla=tsuscripciones&id=".$rs[0]."' class='link_action'><img src='/delete.jpg' border='0'/></a></td></tr>";
	$i+=1;
	}
	mysql_close($db);
	////
	
  $db = mysql_connect("192.168.0.192","mysql_root","fran21");
                                mysql_select_db("clipping",$db);
	$query = "select id,codigo,nombre from ttemas order by id desc";
		$i = 0;
		$con = mysql_query($query,$db);
	
	while ($rs = mysql_fetch_array($con))
	{
	if ((($i+2)%2)==0) $color = '#FFFFFF'; else $color = '#eeeeee';
	$lista_temas.="<tr bgcolor='".$color."' class='texto_verde'><td width='20' >".$rs[0]."</td><td width='170' >".$rs[1]."</td><td width='421'><a href='../principal.php?tema=".$rs[1]."' class='LINKS_MENU'>".$rs[2]."</a></td><td width='21' ></td><td width='18' ><a href='mysql.php?act=delete&tabla=ttemas&id=".$rs[0]."' class='link_action'><img src='/delete.jpg' border='0'/></a></td></tr>";
		$i+=1;
	}
	mysql_close($db);
	
	
  $db = mysql_connect("192.168.0.192","mysql_root","fran21");
                                mysql_select_db("clipping",$db);
	$query = "select id,nombre from tautores order by id desc ";
		$i = 0;
		$con = mysql_query($query,$db);
	while ($rs = mysql_fetch_array($con))
	{
	if ((($i+2)%2)==0) $color = '#FFFFFF'; else $color = '#eeeeee';
	$lista_auto.="<tr bgcolor='".$color."' class='texto_verde'><td width='20' >".$rs[0]."</td><td width='591' >".$rs[1]."</td><td width='21' ></td><td width='18' ><a href='mysql.php?act=delete&tabla=tautores&id=".$rs[0]."' class='link_action'><img src='/delete.jpg' border='0'/></a></td></tr>";
	$i+=1;
	}
	mysql_close($db); 
	
	
	////
	if (isset($_COOKIE['iadmin']))
	{		if (strlen($_COOKIE['iadmin'])>0) $admin = $_COOKIE['iadmin']; }
if (strlen($admin)==0) { header("location: ../index.php?msg=noadmin"); }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
<link rel="stylesheet" href="../style.css" type="text/css">
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script language="javascript" src="../funciones.js" type="text/javascript"></script>
</head>

<body bgcolor="#ffffff">
<form name="login" method="POST" id="login" action="upload.php" enctype="multipart/form-data">
<table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
	<table width="680" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td  colspan="2" align="center" valign="top"><script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','680','height','180','src','../logo2','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../logo2' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="680" height="180">
		<param name="movie" value="../logo2.swf">
		<param name="quality" value="high">
		<embed src="../logo2.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="680" height="180"></embed>
    	</object></noscript></td>
  </tr>

	<tr>
    <td height="6" colspan="2" align="center" ></td>
	</tr>
	<tr>
    <td height="20" colspan="2" align="center" valign="middle"><table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
<td width="120" align="center" ><a href="../index.php"  onMouseOver="javascript:document.getElementById('hom').style.background='#f5f5f5';" onMouseOut="javascript:document.getElementById('hom').style.background='#ffffff';" target="_self" class="LINKS_MENU">
					<div id="hom">Home</div>
				</a></td>
   
   
   <td width="120" align="center" ><a href="mysql.php"  onMouseOver="javascript:document.getElementById('hom2').style.background='#f5f5f5';" onMouseOut="javascript:document.getElementById('hom2').style.background='#ffffff';" target="_self" class="LINKS_MENU">
					<div id="hom2">Visualizar datos</div>
				</a></td>
				
    <td width="120" align="center" ><a href="upload.php"  onMouseOver="javascript:document.getElementById('hom3').style.background='#f5f5f5';" onMouseOut="javascript:document.getElementById('hom3').style.background='#ffffff';" target="_self" class="LINKS_MENU">
					<div id="hom3">Cargar</div>
				</a></td>
				   <td width="120" align="center" ><a href="../index.php?logout=yes"  onMouseOver="javascript:document.getElementById('hom4').style.background='#f5f5f5';" onMouseOut="javascript:document.getElementById('hom4').style.background='#ffffff';" target="_self" class="LINKS_MENU">
					<div id="hom4">Salir</div>
				</a></td>
    </tr>

</table>
</td></tr>
  <!--
  <tr><td colspan="2"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>
	<tr><td colspan="2"><a class='LINKS_MENU' href='?CIETT=1'>Activar CIETT</a> <a class='LINKS_MENU' href='?CIETT=0'>Desactivar CIETT</a></td></tr>
        <tr><td colspan="2"><a class='LINKS_MENU' href='?FAETT=1'>Activar FAETT</a> <a class='LINKS_MENU' href='?FAETT=0'>Desactivar FAETT</a></td></tr>
	<tr><td colspan="2"><a class='LINKS_MENU' href='?CLETT=1'>Activar CLETT</a> <a class='LINKS_MENU' href='?FAETT=0'>Desactivar CLETT</a></td></tr>
        <tr><td colspan="2"><a class='LINKS_MENU' href='?WORK=1'>Activar WORK</a> <a class='LINKS_MENU' href='?WORK=0'>Desactivar WORK</a></td></tr>
        -->
  

	<tr>
    <td align="left" valign="top"><table width="680" border="0" cellspacing="0" cellpadding="2">
	<?php if ($SCREEN!="") echo "<tr><td colspan='2' align='center' bgcolor='#FEEBC7' class='lnk_pagina_actual style1'>&nbsp;* ".$SCREEN."</td></tr>"; ?>
	<tr>
    <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("suscripcion");>: Ver Notas :</td>
    <td width="474"></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td colspan="2">
	<div id="suscripcion" style=" <?php if ($_GET['tabla']=="tnotas") echo "visibility: visible;"; else echo "visibility: hidden;height:1px;"; ?> overflow:auto;">
	<table width="660" border="0" cellspacing="0" cellpadding="1">
     <tr bgcolor="#006666" class="TITULO_BLANCO">
        <td width="20" >Id</td>
        <td width="170" >Tema</td>
        <td width="421" >Titulo</td>
		  <td width="21" >[m]</td>
		  <td width="18" >[e]</td>
     </tr>
	<?php echo $lista_notas; ?>
 </table>
	</div><?php echo $anterior; ?></td></tr>





	<tr>
    <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("suscripcion2");>: Ver Suscripciones :</td>
    <td width="474"></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td colspan="2">
	<div id="suscripcion2" style=" <?php if ($_GET['tabla']=="tsuscripciones") echo "visibility: visible;"; else echo "visibility: hidden;height:1px;"; ?> overflow:auto;">
	<table width="660" border="0" cellspacing="0" cellpadding="1">
     <tr bgcolor="#006666" class="TITULO_BLANCO">
        <td width="20" >Id</td>
        <td width="170" >Tema</td>
		<td width="170" >Usuario</td>
        <td width="100" >Contrase�a</td>
		<td width="100" >Tipo</td>
		<td width="51" >Estado</td>
		
		  <td width="18" >[e]</td>
     </tr>
	<?php echo $lista_susc; ?>
 </table>
	</div></td></tr>


	<tr>
    <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("suscripcion3");>: Ver Medios :</td>
    <td width="474"></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td colspan="2">
	<div id="suscripcion3" style=" <?php  if ($_GET['tabla']=="tautores") echo "visibility: visible;"; else echo "visibility: hidden;height:1px;"; ?> overflow:auto;">
 <table width="660" border="0" cellspacing="0" cellpadding="1">
     <tr bgcolor="#006666" class="TITULO_BLANCO">
        <td width="20" >Id</td>
        <td width="591" >Nombre</td>
		  <td width="21" >[m]</td>
		  <td width="18" >[e]</td>
     </tr> 
	<?php  echo $lista_auto; ?>
 </table>
	</div></td></tr> 

<tr>
    <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("suscripcion4");>: Ver Temas :</td>
    <td width="474"></td>
  </tr>
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td colspan="2">
	<div id="suscripcion4" style=" <?php if ($_GET['tabla']=="ttemas") echo "visibility: visible;"; else echo "visibility: hidden;height:1px;"; ?> overflow:auto;">
	<table width="660" border="0" cellspacing="0" cellpadding="1">
     <tr bgcolor="#006666" class="TITULO_BLANCO">
        <td width="20" >Id</td>
        <td width="191" >Codigo</td>
		        <td width="400" >Nombre</td>
		  <td width="21" >[m]</td>
		  <td width="18" >[e]</td>
     </tr>
	<?php echo $lista_temas; ?>
 </table>
	</div></td></tr>

</table>
</td>
  </tr>
 
     </table></td>
  </tr>
 
</table>
</form>
</body>
</html>
