<?php
ini_set("display_errors", "false");
include_once 'config.db.php';
include_once 'class.db.php';
$URL = "http://www.clipdenoticias.com/manpower2/";
//$URL="http://localhost/clip/manpower_new/";
$categoria = "";
$dia_refresh = "Mon";
$hoy = date('Ymd', time());
$tema_default = "Manpower";
$month = array('Enero', "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$tema = isset($_GET['tema']) ? $_GET['tema'] : $tema_default;
$CPP = 20; //Views per page
$titulo_barra = "Resumen de noticias ";
$rs_categoria_ant = 0;

$getema = "&tema=" . $tema;
// del ultimo Lunes a 6 dias atrás.
/* Para hacer una excepcion:
 * se supone que el 25/10 "Monday" es feriado asi que hacemos
 * $cambio_tope['2011-10-25']='2011-10-26';
 * $cambio_inicial['2011-10-25']=7;
 */

$dias_de_clip = 6;
/* Viene fecha por GET */
if (isset($_REQUEST['fecha'])) {
    $fecha_tope = $_REQUEST['fecha'];
    $fecha_tope_to_date = strtotime($fecha_tope);
    $fecha_inicial = date('Y-m-d', $fecha_tope_to_date - ($dias_de_clip * 24 * 3600));
    if (isset($cambio_inicial[$fecha_tope])) {
        $fecha_inicial = $cambio_inicial[$fecha_tope];
    }
    /* Fecha default */
} else {
    $i = 0;
    $cambio_tope = null;
    while (date('D', time() - $i * 3600 * 24) != $dia_refresh)
        $i++;
    $fecha_tope = date('Y-m-d', time() - $i * 3600 * 24);
    $fecha_inicial = date('Y-m-d', time() - ($i + $dias_de_clip) * 3600 * 24);
    if (isset($cambio_tope[$fecha_tope])) {
        $fecha_tope = $cambio_tope[$fecha_tope];
        if (isset($cambio_inicial[$fecha_tope])) {
            $fecha_inicial = $cambio_inicial[$fecha_tope];
        }
    }
}


$SEARCH = (isset($_REQUEST['search']) && strlen($_REQUEST['search'])) ? 1 : 0;
$STR_SEARCH = ($SEARCH) ? $_REQUEST['search'] : "";
$condicion_buscar = ($SEARCH) ? " and (n.titulo like '%$STR_SEARCH%' or n.copete like '%$STR_SEARCH%' ) " : "";
if ($SEARCH) {
    $fecha_inicial = date('Y', time())."-00-00";
}

$listado_paises = "";
$listado_conti = "";
$temaString = $tema;
if ($temaString == "nom")
    $temaString = "Nombramientos";
if ($temaString == "Recursos Humanos")
    $temaString = "Mercado Laboral";
if ($temaString == "Competencia Manpower")
    $temaString = "Competencia";
$MOSTRAR_SECTORES = ($tema == "Oportunidades de Negocios") ? 1 : 0;
$WIDTH_CLIP = ($MOSTRAR_SECTORES) ? "205" : "235"; //"135":"225";
//
$condicion_id_categoria = "";
if (isset($_REQUEST['category']) && intval($_REQUEST['category']) > 0) {
    $condicion_id_categoria = " and c.id = " . $_REQUEST['category'];
    $categoria = $_REQUEST['category'];
}

if (isset($_REQUEST['id_nota'])) {
    $ID_NOTA = intval($_REQUEST['id_nota']);
}
$posicion_listado = 0;
$condicion_listado = 'GROUP BY n.region, n.tamimg ';
/* Muestra la noticia seleccionada */
if ($ID_NOTA) {
    $dbNota = new consulta("SELECT n.id AS id,
            n.codigo_tema as tema,
            DATE_FORMAT(fecha_nota, '%d/%m/%Y') as fecha,
            n.titulo as titulo,
            n.volanta as volanta,
            n.copete as copete,
            n.texto as texto,
            f.nombre as medio,
            n.region as region,
            n.tamimg as id_categoria
            FROM tnotas n, tautores f
            WHERE n.fuente = f.id and n.id = $ID_NOTA;");
    if ($dbNota->cant) {
        $fila = mysql_fetch_object($dbNota->result);
        if ($fila->id_categoria > 0) {
            $tema = "Oportunidades de Negocios";
            $condicion_listado = " and n.id <> $fila->id and n.tamimg = '$fila->id_categoria' ";
        } else {
            $condicion_listado = " and n.id <> $fila->id and n.region = '$fila->region' ";
        }
        $posicion_listado = 1;
        $listado_paises.="
                    <tr><td colspan='2' width='" . intval(intval($WIDTH_CLIP) * 2) . "' valign='top'>
                        <table border='0' cellpadding='1' cellspacing='0'>
                ";
        $listado_paises.="<tr><td align='right' style='font-size: 11px; color: #777777;'>Por <span style='color:firebrick'>$fila->medio</span> el " . $fila->fecha . "</td></tr>";
        if (strlen($fila->volanta)) {
            $listado_paises.="<tr><td align='left' style='font-size: 12px; color: #666666; padding: 4px;'>" . nl2br($fila->volanta) . "</td></tr>";
        }
        $listado_paises.="<tr><td align='left' style='font-size: 16px; color: #3E8BB7; padding: 4px;'>" . nl2br($fila->titulo) . "</td></tr>";
        if (strlen($fila->copete)) {
            $listado_paises.="<tr><td align='left' style='font-size: 14px; color: #666666; padding: 4px;'>" . nl2br($fila->copete) . "</td></tr>";
        }

        $listado_paises.="<tr><td><hr size=1 noshade='noshade' color='#cccccc' /></td></tr>
                    <tr><td align='left' style='font-size: 11px; color: #666666; padding: 4px;'>" . nl2br($fila->texto) . "</td></tr>
                        </table>
                    </td></tr>";
    } else {
        $ID_NOTA = null;
    }
}
if ($SEARCH)
    $condicion_listado = "";
$condicion_categoria = ($tema == 'Oportunidades de Negocios') ? "" : "n.region = o.region and ";
$query = "  SELECT  DATE_FORMAT(n.fecha_nota,'%e'),
                    DATE_FORMAT(n.fecha_nota,'%c'),
                    DATE_FORMAT(n.fecha_nota,'%Y'),
                    n.titulo as titulo, n.volanta as volanta, n.copete as copete,
                    f.nombre, n.id as id, n.tamimg, n.destacado,
                    n.region as region, o.posicion as posicion, c.nombre as categoria
            FROM    tautores f, torden o, tnotas n LEFT JOIN tcategorias c ON n.tamimg = c.id
            WHERE   $condicion_categoria
                    n.codigo_tema = '$tema'
                    $condicion_buscar
                    $condicion_id_categoria
                    and f.id = n.fuente
                    and o.posicion = 0
                    and n.estado = 1
                    and ( n.fecha_nota >= '$fecha_inicial' and n.fecha_nota <= '$fecha_tope')
                    $condicion_listado
            ORDER BY n.region DESC, o.orden ASC, 
                    n.id DESC,  n.destacado DESC;";
$queryReg = "  SELECT  DATE_FORMAT(n.fecha_nota,'%e'),
                    DATE_FORMAT(n.fecha_nota,'%c'),
                    DATE_FORMAT(n.fecha_nota,'%Y'),
                    n.titulo as titulo, n.volanta as volanta, n.copete as copete,
                    f.nombre, n.id as id, n.tamimg, n.destacado,
                    n.region as region, o.posicion as posicion, c.nombre as categoria
            FROM    tautores f, torden o, tnotas n LEFT JOIN tcategorias c ON n.tamimg = c.id
            WHERE   $condicion_categoria
                    n.codigo_tema = '$tema'
                    $condicion_buscar
                    $condicion_id_categoria
                    and f.id = n.fuente
                    and o.posicion = 1
                    and n.estado = 1
                    and ( n.fecha_nota >= '$fecha_inicial' and n.fecha_nota <= '$fecha_tope')
                    $condicion_listado
            ORDER BY n.region DESC, o.orden ASC, 
                    n.id DESC,  n.destacado DESC;";

//Listar noticias de entre begin y end
$mdb = new consulta($query);
$antOrder = 999;
$i = 2;
if ($mdb->cant) {
    while ($r = mysql_fetch_object($mdb->result)) {
        if ((($tema == 'FAETT' or $tema == 'CLETT&A' or $tema == 'CIETT' or $tema == 'work') and $antOrder !== $r->orden)
                or ($tema !== 'work' and $tema !== 'FAETT' and $tema !== 'CIETT' and $tema !== 'CLETT&A')) {
            $antOrder = $r->orden;

            $destacado = intval($r->destacado);
            $destacadoImg = $destacado ? "<img src='http://www.clipdenoticias.com/manpower2/imagenes/destacado.png' border='0' /> " : "";

            $if_tr = ($i % 2 == 0) ? "<tr>" : "";
            $listado_paises.=$if_tr;
            $listado_paises.="
            <td width='$WIDTH_CLIP' valign='top'>
                <table border='0' cellpadding='1' cellspacing='0'>
                    <tr><td align='left' style='padding-left: 8px; font-size:12px; background-color:#D7E8F0; color:#3E8BB7; font-style: italic; font-weight: bold;'>" . $r->region . "</td></tr>
                    <tr><td align='left' style='padding-left: 8px; font-size:10px; font-weight: bold;'><a style='color: #333333;font-size:11px; font-weight:bold;text-decoration:none;' href='$URL?tema=$tema&id_nota=$r->id'>" . nl2br($r->titulo) . "</a></td></tr>
                    <tr><td align='left' style='padding-left: 8px; font-size:10px;'>" . nl2br($r->copete) . "</td></tr>
                    <tr><td align='right'><a style='text-decoration: none; color: #333333; font-weight: normal;padding-right: 10px; font-size:11px; font-weight: bold; text-align:right;color: #F99D38;' href='$URL?tema=$tema&id_nota=$r->id'>Ver m&aacute;s noticias &gt;</a></td></tr>
                </table>
            </td>";
            $i++;
            $if_tr = ($i % 2 == 0) ? "</tr>" : "";
            $listado_paises.=$if_tr;
        }
    }
    if ($if_tr != "</tr>") {
        $listado_paises.="<td width='$WIDTH_CLIP'></td></tr>";
        $if_tr = "</tr>";
    }
} else {
    if (!$ID_NOTA)
        $listado_paises.="
            <td colspan=2 width='" . intval(2 * intval($WIDTH_CLIP)) . "' valign='top' style='font-size: 11px; color: #333333; text-align:center;padding-top:20px'> No hay novedades para mostrar.</td>";
}



$mdb2 = new consulta($queryReg);
$antOrder = 999;
$i = 2;
if ($mdb2->cant) {
    while ($r = mysql_fetch_object($mdb2->result)) {
        if ((($tema == 'FAETT' or $tema == 'CIETT' or $tema == 'CLETT&A' or $tema == 'work') and $antOrder !== $r->orden)
                or ($tema !== 'work' and $tema !== 'FAETT' and $tema !== 'CIETT' and $tema !== 'CLETT&A')) {
            $antOrder = $r->orden;

            $destacado = intval($r->destacado);
            $destacadoImg = $destacado ? "<img src='http://www.clipdenoticias.com/manpower2/imagenes/destacado.png' border='0' /> " : "";

            if ($if_tr != "</tr>") {
                $listado_paises.="<td width='$WIDTH_CLIP'></td></tr>";
                $if_tr = "</tr>";
            }
            $listado_conti.="
                <tr><td align='left' valign='top' >
                    <table border='0' style='background:url('" . $URL . "imagenes/newsletter-24.gif') repeat-y;' width='133' cellpadding='1' cellspacing='0'>";
            if ($posicion_listado == 0)
                $listado_conti.="<tr><td align='left' style='padding-left: 8px; font-size:12px; background:#D7E8F0; color:#333333; font-style: italic; font-weight: normal;'>" . $r->region . "</td></tr>";
            $listado_conti.="<tr><td align='left' style='padding-left: 8px; font-size:10px; font-weight: bold;'><a style='color: #333333;font-size:11px; font-weight:bold;text-decoration:none;' href='$URL?tema=$tema&id_nota=$r->id'>" . nl2br($r->titulo) . "</a></td></tr>";
            $listado_conti.="<tr><td align='left' style='padding-left: 8px; font-size:10px;'>" . nl2br($r->copete) . "</td></tr>
                        <tr><td align='right'><a style='text-decoration: none; color: #333333; font-weight: normal;padding-right: 10px; font-size:11px; font-weight: bold; text-align:right;color: #F99D38;' href='$URL?tema=$tema&id_nota=$r->id'>Ver m&aacute;s noticias &gt;</a></td></tr>
                    </table>
                </td></tr>";
            $i++;
        }
    }
}
/// Historial ///
$listado_history = "";
$i = 0;
$c = 0;
while ($c < 5) {

    if (date('D', time() - $i * 3600 * 24) == $dia_refresh) {
        $fecha_usada = isset($cambio_tope[date('Y-m-d', time() - $i * 3600 * 24)]) ? $cambio_tope[date('Y-m-d', time() - $i * 3600 * 24)] : date('Y-m-d', time() - $i * 3600 * 24);
        $str_fecha = date('j', time() - $i * 3600 * 24) . " de " . $month[date('n', time() - $i * 3600 * 24) - 1];
        $listado_history.="
            <tr><td align='left' style='padding: 4px; border-bottom: solid 1px #CCCCCC; padding-left: 8px; font-size:9px; font-weight: bold; background: #EEEEEE; color:#444444;'><a style='text-decoration: none; color: #333333; font-weight: normal;' href='$URL?fecha=$fecha_usada&tema=$tema&category=$categoria'>" . $str_fecha . "</a></td></tr> ";
        $c++;
    }
    $i++;
}
/*
 *  Buscador
 */


/* Listado sectores */
if ($MOSTRAR_SECTORES) {
    $i = 0;
    $mdb = new consulta("SELECT * from tcategorias order by id ASC;");
    $listado_sectores = "";
    if ($mdb->cant) {

        while ($r = mysql_fetch_object($mdb->result)) {
            $i++;
            $listado_sectores.="
                <tr><td align='left' style='padding: 8px; border-bottom: solid 1px #BBBBBB; padding-left: 8px; font-size:11px; background-image:url('" . $URL . "imagenes/fondo_sector.gif') repeat-y; color:#333333;";
            if ($i == $mdb->cant)
                $listado_sectores.="border: 0px;";
            $listado_sectores.="'><a style='text-decoration: none; color: #333333; font-weight: normal;' href='$URL?fecha=$fecha_tope&tema=$tema&category=$r->id'>$r->nombre</td></tr>";
        }
    }
}

$FAETT = 0;
$CIETT = 0;
$CLETT = 0;
$opts = new consulta("SELECT * FROM topciones");
while ($ss = mysql_fetch_array($opts->result)) {
    $CIETT = intval($ss['CIETT']);
    $CLETT = intval($ss['CLETT']);
    $FAETT = intval($ss['FAETT']);
    $WORK = intval($ss['WORK']);
}

echo "<!-- $query -->";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
        <link rel="stylesheet" href="<?php echo $URL; ?>style.css" type="text/css">
        <link rel="alternate" type="application/rss+xml" title="RSS .92" href="http://www.clipdenoticias.com/manpower/rss/index.php" />
        <script src="../funciones.js" type="text/javascript"></script>
        <script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

    </head>

    <body bgcolor="#ffffff" style="margin: 0 auto 0 auto; font-family: Arial; font-size: 12px; color: #333333;">
        <form method="GET" action="index.php">
            <table width="700" border="0"   align="center" cellpadding="0" cellspacing="0">
                <tr><td>
                        <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
                            <!-- LOGO -->
                            <tr><td   align="center" valign="top">
                                    <div style="float:left;z-index:100">
                                        <a  href='http://www.manpower.com' 
                                            style="text-decoration: none; color: #333333; font-weight: normal;" 
                                            target="_blank"><img src="<?php echo $URL; ?>imagenes/logo2.jpg"  
                                                             border="0"></a>
                                    </div>
                                   <!-- <div 
                                        style="z-index:101;float:right;padding-right:40px;font-family:Verdana; color: gray;padding-top:2px; font-size:13px;font-weight:normal;margin-top:-30px;">
                                        Del <?php echo date('d', strtotime($fecha_inicial)) . " al " . date('d.m.Y', strtotime($fecha_tope)); ?>
                                    </div>-->

                                </td>
                            </tr>
                          <!--  <tr><td>
                                    <div style="float:left;color: black;padding-top:2px; font-size:9px;font-weight:bold;">Per&iacute;odo del <?php echo date('d.m.Y', strtotime($fecha_inicial)) . " al " . date('d.m.Y', strtotime($fecha_tope)); ?></div>
                                    
                                </td></tr>-->
                            <!-- MENU 1 -->
                            <tr>
                                <td height="18"  align="center" valign="middle">
                                    <table width="710" border="0" cellspacing="0" cellpadding="0"><tr>
                                            <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;" bgcolor="#FFA33A" ><a style="text-decoration: none; color: #333333; font-weight: normal; " href="<?php echo $URL; ?>?tema=Manpower&BuscarFecha=Buscar&page=1">MANPOWER</a></td>
                                            <?php
                                            if ($FAETT) {
                                                ?>
                                                <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;"  bgcolor="#E3E0E0"><a style="text-decoration: none; color: #333333; font-weight: normal;" href="<?php echo $URL; ?>?tema=FAETT&BuscarFecha=Buscar&page=1">FAETT</a></td>
                                                <?php
                                            } elseif ($WORK) {
                                                ?>
                                                <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;"  bgcolor="#E7E7E7"><a style="text-decoration: none; color: #333333; font-weight: normal;" href="<?php echo $URL; ?>?tema=work&BuscarFecha=Buscar&page=1">Empleo Temporario</a></td>
                                                <?php
                                            }
                                            if ($CIETT) {
                                                ?>
                                                <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;"  bgcolor="#E3E0E0"><a style="text-decoration: none; color: #333333; font-weight: normal;" href="<?php echo $URL; ?>?tema=CIETT&BuscarFecha=Buscar&page=1">CIETT</a></td>
                                                <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;"  bgcolor="#DDDDDD"><a style="text-decoration: none; color: #333333; font-weight: normal;"  href="<?php echo $URL; ?>?tema=Competencia Manpower&BuscarFecha=Buscar&page=1">Competencia</a></td>
                                                   <?php
                                            } else {
                                                    if ($CLETT){
                                                ?>
                                                 <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;"  bgcolor="#E3E0E0"><a style="text-decoration: none; color: #333333; font-weight: normal;" href="<?php echo $URL; ?>?tema=CLETT%26A&BuscarFecha=Buscar&page=1">CLETT&amp;A</a></td>
                                                <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;"  bgcolor="#DDDDDD"><a style="text-decoration: none; color: #333333; font-weight: normal;"  href="<?php echo $URL; ?>?tema=Competencia Manpower&BuscarFecha=Buscar&page=1">Competencia</a></td>
                                                 <?php
                                                    }else{?>
                                                <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;" bgcolor="#DDDDDD"><a style="text-decoration: none; color: #333333; font-weight: normal;"   href="<?php echo $URL; ?>?tema=Competencia Manpower&BuscarFecha=Buscar&page=1">Competencia</a></td>
                                                <?php }
                                            }
                                            ?>
                                            <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;" bgcolor="#D8D4D4"><a style="text-decoration: none; color: #333333; font-weight: normal;"   href="<?php echo $URL; ?>?tema=Recursos Humanos&BuscarFecha=Buscar&page=1">Mercado Laboral</a></td>
                                            <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;" bgcolor="#CCCCCC"><a style="text-decoration: none; color: #333333; font-weight: normal;"   href="<?php echo $URL; ?>?tema=nom&BuscarFecha=Buscar&page=1">Nombramientos</a></td>
                                            <td style="font-family:Arial; text-align:center; padding: 2px; font-size:11px;" bgcolor="#BFBFBF"><a style="text-decoration: none; color: #333333; font-weight: normal;"  href="<?php echo $URL; ?>?tema=Oportunidades de Negocios&BuscarFecha=Buscar&page=1">Oportunidades de Negocios</a></td>
                                        </tr></table>
                                </td>
                            </tr>
                            <!-- MENU 2 -->
                            <tr>
                                <td>
                                    <table  width="700" border="0" cellpadding="0" align="left" cellspacing="0">
                                        <tr>
                                            <td height="24" align="left"><img alt="" src="<?php echo $URL; ?>imagenes/td_paises.png" /></td>
                                            <td width="132" align="left" height="24"><img alt="" src="<?php echo $URL; ?>imagenes/td_reg.png" /></td>
                                            <td width="92" align="left" height="24"><img alt="" src="<?php echo $URL; ?>imagenes/td_hist.png" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr><td   align="left" valign="top">
                                    <table border="0" cellpadding="0" width="710" cellspacing="0"><tr><td valign="top">
                                                <table  style="border:solid 1px #CCCCCC;"  cellspacing="0" cellpadding="0">
                                                    <tr><td valign="top" align="left">
                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                               <!--  <tr><td colspan="2" style="padding:4px;font-size:11px;" align="right">
                                                                     <div style="float:left;">Usted est&aacute; leyendo las noticias de la secci&oacute;n <strong>Manpower</strong>
                                                                       </div><div style="float:right;color: #2191c0;padding-top:2px; font-size:9px;">Per&iacute;odo del <?php echo date('d.m.Y', strtotime($fecha_inicial)) . " al " . date('d.m.Y', strtotime($fecha_tope)); ?></div>
                                                                   </td></tr>-->

<!-- <tr><td colspan="2" align="left" style="padding:4px;font-size:11px; color: #FF8821;text-align:left;" align="right">Esta semana, la Encuesta de Expectativas de Empleo de Manpower para el 4to Trimestre del a&ntilde;o, ha tenido una repercusi&oacute;n de m&aacute;s de 100 noticias a nivel regional.</td></tr>-->
                                                                <?php echo $listado_paises; ?>
                                                            </table>


                                                        </td>
                                                        <!-- CONTINENTES -->
                                                        <?php if (!$MOSTRAR_SECTORES) { ?>
                                                            <td valign="top" width="140" align="right">
                                                                <table  align="right" cellpadding="1" cellspacing="0" border="0">
                                                                    <?php echo $listado_conti; ?>

                                                                </table>
                                                            </td>
                                                        <?php } ?>
                                                        <?php
                                                        //$MOSTRAR_SECTORES = 0;
                                                        if ($MOSTRAR_SECTORES) {
                                                            ?>
                                                            <!-- SECTORES -->
                                                            <td valign="top" width="180" align="right">
                                                                <table width="160" align="right" cellpadding="1" cellspacing="0" border="0">
                                                                    <?php echo $listado_sectores; ?>
                                                                </table>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                </table>
                                            </td><!-- HISTORIAL -->
                                            <td valign="top" align="left">

                                                <table border="0" cellpadding="0" cellspacing="0"><tr><td colspan="2">
                                                            <table width="96" align="left" cellpadding="1" cellspacing="0" border="0">
                                                                <?php echo $listado_history; ?>
                                                            </table></td></tr> <tr><td height="12"></td></tr>
                                                    <tr><td style="font-size: 11px; color: #555555;">&gt; Buscar</td></tr><tr><td><input type="text" name="search" style="border: solid 1px #cccccc; width:90px; height:20px; padding:2px; font-size: 11px;" id="txtBuscar" value="" />
                                                        </td><td><img alt="Buscar" src="http://www.clipdenoticias.com/manpower2/imagenes/buscar.gif" onclick="document.forms[0].submit()" border="0" style="cursor:pointer;" />
                                                        </td></tr> 
                                                    <tr><td colspan="2" valign="top" style="padding-top:10px;" height="180">

                                                        </td></tr>
                                                    <tr><td colspan="2">
                                                            <img src="http://www.clipdenoticias.com/manpower2/imagenes/_logo_historial1.jpg" border="0"/>
                                                        </td></tr>

                                                </table>
                                            </td>
          <!--  <td valign="top" align="left">
                <table border="0" cellpadding="0" cellspacing="0"><tr><td colspan="2">
                    <table width="96" align="left" cellpadding="1" cellspacing="0" border="0">
                                            <?php echo $listado_history; ?>
                    </table></td></tr>
                    
                </table>
            </td>-->
                                        </tr></table></td></tr>
                            <!-- FOOTER -->
                            <!--<tr><td  align="center" style="font-size:11px;">
                                    <span style='font-size:11px'><br />La edici&oacute;n del newsletter es supervisada por el &aacute;rea de Comunicaciones de la oficina regional.</span>
                                <br/>Si desea que demos de baja su direcci&oacute;n env&iacute;e un email a jorgelina.calvente@manpower.com.ar</td>
                            </tr>-->
                        </table>
                    </td></tr>
            </table>
        </form>
    </body>
</html>
