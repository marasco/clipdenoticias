<?php

function texto($a) {
    $a = str_replace("<", "&lt;", $a);
    $a = str_replace(">", "&gt;", $a);
    $a = str_replace("'", "&quot;", $a);
    $a = str_replace(chr(34), "&quot;", $a);

    return $a;
}

function logout($nombre_cook) {
    setcookie($nombre_cook, "", 0, "/");
    setcookie("iadmin", "", 0, "/");
    header("Location: index.php?ok");
}

function aleatoria($cant) {
    for ($j = 1; $j <= $cant; $j++)
        $nombre.=rand(0, 9);
    return $nombre;
}

;

function validar_str($string) {
    $string = str_replace("'", chr(34), $string);
    $string = str_replace(";", ",", $string);
    $string = str_replace("<", "(", $string);
    $string = str_replace(">", ")", $string);
    return $string;
}

if ($_GET['id'] == "")
    header("Location: " . $_SERVER['HTTP_REFERER']);
$CPP = 10; //Views per page
$lista_meses = array(
    0 => "Enero",
    1 => "Febrero",
    2 => "Marzo",
    3 => "Abril",
    4 => "Mayo",
    5 => "Junio",
    6 => "Julio",
    7 => "Agosto",
    8 => "Septiembre",
    9 => "Octubre",
    10 => "Noviembre",
    11 => "Diciembre");

if (isset($_COOKIE['usuarios'])) {
    if (strlen($_COOKIE['usuarios']) > 0)
        $user = $_COOKIE['usuarios'];
} else {
    if ((strlen($_COOKIE['iadmin']) > 0))
        $user = "admin";
}
if (strlen($user) == 0) {
    //   header("location: index.php?msg=nologin");
}

$suscripcion = 0;
$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);
//echo $user;
/*
  if ($user != "admin") {
  $query = "select S.codigo_tema 'd1', S.estilo 'd2', S.fecha_creacion 'd3' from tsuscripciones S where S.usuario = '" . $user . "'";
  $con = mysql_query($query, $db);
  while ($rs = mysql_fetch_array($con)) {
  $suscripcion = 1;
  $tema = $rs[0];
  $hojaestilo = $rs[1];
  }
  mysql_close($db);
  if ($suscripcion == 0) {
  header("location: index.php?msg=nosusc");
  }
  ///	echo $suscripcion."AA";
  } else {
  //	echo $user."AA";
  }
 */
$tema = 'rio';
/* if ($tema == "") {  header("location: index.php?msg=notema");  } */
if ($hojaestilo == "default") {
    $hojaestilo = "";
} else {
    $hojaestilo = "<link rel='stylesheet' href='./rio.css' type='text/css'>";
}

//Paginacion
//Listar noticias de entre begin y end


$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);
$query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.codigo_tema, n.tamimg
				from tnotas n, tautores f where 
				 f.id = n.fuente and n.id = " . $_GET['id'] . " and n.estado = 1";
//echo $query;
$con = mysql_query($query, $db);
$cant_not = 0;
while ($rs = mysql_fetch_array($con)) {
    //echo "pepe";
    $cant_not++;
    $titulo = $rs[3];
    $volanta = $rs[4];
    $resumen = nl2br($rs[6]);
    $tema = $rs[9];
    $fecha = $rs[0] . " de " . $lista_meses[$rs[1] - 1] . " de " . $rs[2];
    //$texto = nl2br($rs[6]);
    $autor = $rs[7];
    $idn = $rs[8];
    $tamimg = $rs[10];
    $copete = $rs[5];
    $code_img = "";
    for ($ppp = 1; $ppp < 7; $ppp++) {
        if (file_exists("imgnotas/" . $idn . "_" . $ppp . ".jpg")) {
            $code_img .= "<img src='/imgnotas/" . $idn . "_" . $ppp . ".jpg'  /><br>";
        }
    }

    $listado_notas.=
            "<tr><td class='volanta'>" . $volanta . "</td></tr>
		<tr><td bgcolor='#FFFFFF' class='titulo'><a href='vernota.php?id=" . $idn . "' class='titulo'>" . $titulo . "</a></td></tr><tr><td class='copete'>" . $copete . "</td></tr>
		<tr><td height='1' valign='middle' bgcolor='#cccccc'></td></tr>
		<tr><td class='texto'>" . $resumen . "</td></tr>";
    //"<tr><td align='right' valign='middle'><a href='vernota.php?id=".$idn."' class='vernota'>Ver nota completa</a></td></tr>


    $listado_notas.="<tr><td height='20' align='right' valign='middle' bgcolor='#ffffff'><span class='fondo_gris'><span class='fuente'>&nbsp;Fuente: <span style='color:#122384;'>" . $autor . "</span></span><span class='fecha'>, " . $fecha . "&nbsp;</span></span></td></tr><tr><td>" . $code_img . "</td></tr>
		<tr><td height='2' align='right' valign='bottom' bgcolor='#333333'></td></tr>";
}
mysql_close($db);
if ($cant_not < 1)
    $listado_notas.= "<tr><td class='texto'>No hay notas con el criterio buscado.<br><br><a href='http://www.clipdenoticias.com/rio' class='vernota'>:: Volver</a></td></tr>";
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>rio - Clipping de Noticias</title>
        <link rel="stylesheet" href="./main.css" type="text/css">
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
                                <td  colspan="3" align="center" valign="top">
                                    <div align="center" style="background:#FFF; color: #777; font-size:18px; font-family: Helvetica; padding:4px;text-align:center;" >
                                        <div style="float:left;padding-right: 20px;"><a href="/rio/" border="0"><img src="./logo.png"  border="0" /></a></div>
                                        <div style="float:left; padding:20px;">Clipping de Noticias</div> </div>
                                </td>
                            </tr>
                            <tr><td colspan="3"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>

                            <tr>
                                <td width="700"  align="center" valign="top">
                                    <table width="700" style="border:solid 1px; border-color:#fcfcfc" border="0" cellspacing="6" cellpadding="0">
                                        <tr>
                                            <td height="30" valign="middle" class="texto"><a  class="vernota" href="javascript:history.go(-1);"> Volver </a> - <a  class="vernota" href="javascript:window.print()"> Imprimir </a></td>
                                        </tr>
                                        <?php echo $listado_notas; ?>


                                    </table>

                                </td> 
                            </tr>

                        </table></td>
                </tr>

            </table>
        </form>
    </body>
</html>
