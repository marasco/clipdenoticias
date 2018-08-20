<?php
@require_once '../env.php';

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
$CPP = 10; //Views per page
$titulo_barra = "Resumen de noticias";
$filtro_fecha_hoy = " and n.fecha_nota = CURRENT_DATE() ";
$dia_h = date("D");
//echo $dia_h."<br>";
switch ($dia_h) {
    case "Sun": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-2 ";
    case "Sat": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-1 ";
    case "Mon": $filtro_dia_hoy = " and ( n.fecha_nota = CURRENT_DATE()-2 or n.fecha_nota = CURRENT_DATE()-1 or n.fecha_nota = CURRENT_DATE() )  ";
}




if (isset($_COOKIE['usuarios'])) {
    if (strlen($_COOKIE['usuarios']) > 0)
        $user = $_COOKIE['usuarios'];
    //echo "HAY LOGIN".$_COOKIE['usuarios'];sf
} else {
    if ((strlen($_COOKIE['iadmin']) > 0) && ($_GET['tema'] != ""))
        $user = "admin";
    //echo "HAY ADMIN";
}
//echo strlen($user);
if (strlen($user) == 0) {
    //  header("location: index.php?msg=nologin");
}
//echo "USUARIO: ".$usuario;
//Ya verifique el login
//Verifico suscripciones
$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);

$tema = 'san isidro';



//Listar noticias de entre begin y end


$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);
if (($_GET['btnBuscar'] == "Buscar") && ($_GET['search'] != "")) {
    // die("1");
    $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region
				from tnotas n, tautores f where 
				n.codigo_tema = '" . $tema . "' " . $fecha_inicial_s . " and f.id = n.fuente  and n.estado = 1 and ( n.titulo like '%" . $_GET['search'] . "%' or n.copete like '%" . $_GET['search'] . "%' ) order by n.region asc, id desc";
    $titulo_barra = "Resultados de la b&uacute;squeda";
} else {

    if (($_GET['BuscarFecha'] == "Buscar") && ($_GET['fdia'] != "") && ($_GET['fmes'] != "") && ($_GET['fanio'] != "")) {

        $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region
				from tnotas n, tautores f where 
				n.codigo_tema = '" . $tema . "' " . $fecha_inicial_s . " and f.id = n.fuente and n.estado = 1 and n.fecha_nota = '" . $_GET['fanio'] . "-" . $_GET['fmes'] . "-" . $_GET['fdia'] . "' order by n.region asc,  id desc";
        if (isset($_GET['desde'])) {
            $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region
				from tnotas n, tautores f where 
				n.codigo_tema = '" . $tema . "' " . $fecha_inicial_s . " and f.id = n.fuente and n.estado = 1 and n.fecha_nota >= '" . $_GET['desde'] . "' AND n.fecha_nota <= '" . $_GET['fanio'] . "-" . $_GET['fmes'] . "-" . $_GET['fdia'] . "' order by n.fecha_nota desc, n.region asc,  id desc";
        }
        $titulo_barra = "Notas del " . $_GET['fdia'] . "/" . $_GET['fmes'] . "/" . $_GET['fanio'];
        //echo $query;
        //die("3");
    } else {
        $dow = date('D', time());
        if ($dow == 'Mon') {
            
        }
        $orderx = "";

        $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region
				from tnotas n, tautores f where 
				n.codigo_tema = '" . $tema . "' " . $fecha_inicial_s . " and f.id = n.fuente and n.estado = 1 " . $filtro_fecha_hoy . " order by $orderx n.region asc, n.id desc";
    };
}
echo "<!-- $query -->";
$con = mysql_query($query, $db);
$i = 0;
$inicio = 0;
$actual = 1;
if ($_GET['page'] != "")
    $actual = $_GET['page'];
$inicio = ($actual * $CPP) - $CPP;
while ($rs = mysql_fetch_array($con)) {
    if ($i > -1) {
        $titulo = $rs[3];
        $volanta = $rs[4];
        $resumen = nl2br($rs[5]);
        $fecha = $rs[0] . " de " . $lista_meses[$rs[1] - 1] . " de " . $rs[2];
        $autor = $rs[7];
        $idn = $rs[8];
        $tamimg = $rs[9];
        $listado_notas.=
                "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size:10px; color:#888888; '> " . $volanta . "</td></tr>
			<tr><td bgcolor='#FFFFFF' style='font-family:Georgia, Times New Roman, Times, serif; font-size:18px; color:#555555; text-decoration:none;'><a href='http://www.clipdenoticias.com/sanisidro/vernota.php?id=" . $idn . "' 				class='titulo2'>" . $titulo . "</a></td></tr>
			<tr><td height='1' valign='middle' bgcolor='#cccccc'></td></tr>
			<tr><td style='font-family:  Arial, Helvetica, sans-serif; font-size:11px; color:#666666; text-decoration:none;'>" . $resumen . "</td></tr>
			<tr><td align='right' valign='middle'><a href='http://www.clipdenoticias.com/sanisidro/vernota.php?id=" . $idn . "' class='vernota'>Ver nota completa</a></td></tr>
			<tr><td height='20' align='right' valign='middle' bgcolor='#ffffff'><span><span style='font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#333333; font-weight:bold; '>&nbsp;Fuente: <span style='color:#028974;'>" . $autor . "</span></span><span style='font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#333333; '>, " . $fecha . "&nbsp;</span></span></td></tr>
			<tr><td height='2'><hr size='1' noshade='noshade' color='#666666' /></td></tr>";
    };
    $i++;
}

mysql_close($db);
if ($i < 1)
    $listado_notas.= "<tr><td class='texto'>No se han publicado noticias de acuerdo al criterio solicitado.<br><br><a href='javascript:history.go(-1);' class='vernota'>:: Volver</a></td></tr>";

if ($_GET['btnBuscar'] == "Buscar") {
    $CADENA_BUSCAR = "&btnBuscar=Buscar&search=" . $_GET['search'] . "&p=p";
}
if ($_GET['BuscarFecha'] == "Buscar") {
    $CADENA_BUSCAR.= "&BuscarFecha=Buscar&fdia=" . $_GET['fdia'] . "&fmes=" . $_GET['fmes'] . "&fanio=" . $_GET['fanio'];
}

function fecha_diff($data1, $data2) {

    // 86400 seg = 60 [seg/1_minuto] * 60 [1_minuto / 1_hora]* 24 [1_hora]

    $segundos = strtotime($data2) - strtotime($data1);
    $dias = intval($segundos / 86400);
    $sl_retorna = $dias;
    return $sl_retorna;
}

if (isset($_GET['fanio']) && isset($_GET['fmes']) && isset($_GET['fdia'])) {
    $currentDate = $_GET['fanio'] . "-" . $_GET['fmes'] . "-" . $_GET['fdia'];
    $currentDay = intval($_GET['fdia']);
    $currentMonth = intval($_GET['fmes']);
    $currentYear = intval($_GET['fanio']);
} else {
    $currentDate = date('Y-m-d', time());
    $currentDay = date('d', time());
    $currentMonth = date('m', time());
    $currentYear = date('Y', time());
}
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <title>clipdenoticias.com</title> 
        <style type="text/css">
            .TITULO_BLANCO {font-family: Arial, Helvetica, sans-serif; color: #FFFFFF; font-size: 11px; text-decoration:none;}
            .TITULO_BLANCO:hover  {font-family:  Arial, Helvetica, sans-serif; color: #FFFFFF; font-size: 11px; text-decoration:none;}
            /* noticias */
            .volanta {font-family: Arial, Helvetica, sans-serif; font-size:10px; color:#888888; }
            .copete {font-family: Georgia, "Times New Roman", Times, serif;; font-size:12px; color:#888888; }
            .titulo {font-family:Georgia, "Times New Roman", Times, serif; font-size:18px; color:#555555; text-decoration:none; }
            .titulo:hover {font-family:Georgia, "Times New Roman", Times, serif; font-size:18px; color:#006666; background-color:#f9f9f9; text-decoration:none; }
            .titulo2 {font-family:Georgia, "Times New Roman", Times, serif; font-size:18px; color:#555555; text-decoration:none; }
            .titulo2:hover {font-family:Georgia, "Times New Roman", Times, serif; font-size:18px; color:#006666; background-color:#f9f9f9; text-decoration:none; }
            .lnk_pagina_actual  {font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#ff3300; font-weight:bold; text-decoration:none; }
            .lnk_pagina_actual:hover {text-decoration:none; }


            .texto { font-family:  Arial, Helvetica, sans-serif; font-size:11px; color:#666666; text-decoration:none; }
            .texto:hover {text-decoration:none; }

            .vernota {font-family: Arial, Helvetica, sans-serif; font-size:10px; color:#028974; text-decoration:none;}
            .vernota:hover {font-family:  Arial, Helvetica, sans-serif; font-size:10px; color:#028974; text-decoration:underline; }
            .fuente {font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#333333; font-weight:bold; }
            .fecha {font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#333333; }
            .rojo {color:#ff3333;}
            .autor {color:#CC0000;}
            .fondo_gris {background-color:#eeeeee; }
            .titulo_chico {
                font-family: Georgia, "Times New Roman", Times, serif;
                font-size: 11px;
                color:#006666; 
                text-decoration:none; 

            }
            .titulo_chico:hover {
                font-family: Georgia, "Times New Roman", Times, serif;
                font-size: 11px;
                color:#00CC00; 
                background-color:#f9f9f9;
                text-decoration:none; 

            }
            /*FIN NOTICAS*/


            /**/
        </style>
    </head>

    <body bgcolor="#ffffff">

        <table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
            <tr>
                <td  colspan="1" align="center" valign="top">
                    <div style = "background:#FFF; color: #777; font-size:18px; font-family: Helvetica; padding:4px;text-align:left;" >
                        <div style="float:left;padding-right: 20px;"><a href="/sanisidro/" border="0"><img src="http://www.clipdenoticias.com/sanisidro/logo.png?x=8282" border="0" /></a></div>
                        <div style="float:left; padding:20px;">Clipping de Noticias</div> </div>
                </td>
            </tr>


            <tr><td >
                    <hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>

            <tr>
                <td width="600"  align="center" valign="top">
                    <table width="600" style="border:solid 1px; border-color:#fcfcfc" border="0" cellspacing="6" cellpadding="0">
                        <tr>

                            <td style="padding:10px;color:#ffffff;font-family:Arial;" bgcolor="#028974" ><?php echo $titulo_barra; ?></td>

                        </tr>
                        <?php echo $listado_notas; ?>



                    </table>

                </td> 

            </tr>

        </table>

    </body>
</html>
