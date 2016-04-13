<?php
ini_set("display_errors", "false");
include_once 'config.db.php';
include_once 'class.db.php';
$URL = "http://www.clipdenoticias.com/manpower3/";
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
$busqueda_fecha = 0;
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
} elseif ($_REQUEST['BuscarFecha'] == "Buscar" && $_REQUEST['fdia'] && $_REQUEST['fmes'] && $_REQUEST['fanio']) {
    $fecha_tope = $_REQUEST['fanio'] . "-" . $_REQUEST['fmes'] . "-" . $_REQUEST['fdia'];
    $fecha_inicial = $fecha_tope;
    $busqueda_fecha = 1;
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

$reemplazar = 0;
$SEARCH = (isset($_REQUEST['search']) && strlen($_REQUEST['search'])) ? 1 : 0;
$condicion_tema = ($SEARCH) ? " n.codigo_tema like '%%' " : " n.codigo_tema = '$tema' ";
$condicion_tema = ($busqueda_fecha) ? " n.codigo_tema like '%%' " : $condicion_tema;
$STR_SEARCH = ($SEARCH) ? $_REQUEST['search'] : "";
$condicion_buscar = ($SEARCH) ? " and (n.titulo like '%$STR_SEARCH%' or n.copete like '%$STR_SEARCH%' ) " : "";
if ($SEARCH) {
    $fecha_inicial = date('Y', time()) . "-00-00";
    $reemplazar = 1;
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
if (isset($_REQUEST['id_nota'])) {
    $ID_NOTA = intval($_REQUEST['id_nota']);
    $MOSTRAR_SECTORES = 0;
}
$WIDTH_CLIP = ($MOSTRAR_SECTORES) ? "212" : "212"; //"205" : "225"; //"135":"225";
// SECTORES
/* Listado sectores */
if ($MOSTRAR_SECTORES) {
    $i = 0;
    $mdb = new consulta("SELECT * from tcategorias order by nombre ASC;");
    $listado_sectores = "";
    $arr_sectores = array();
    if ($mdb->cant) {

        while ($r = mysql_fetch_object($mdb->result)) {
            $arr_sectores[$r->id] = $r->nombre;
            $arr_title[$r->id] = 0;
            $i++;
            $listado_sectores.="
                <tr><td align='left' class='td_tit_sectores ";
            if ($i == $mdb->cant)
                $listado_sectores.=" sin_linea";
            $listado_sectores.="'><a href='$URL?fecha=$fecha_tope&tema=$tema&category=$r->id'>$r->nombre</td></tr>";
        }
    }
}
//
$condicion_id_categoria = "";
$categoria = null;
$condicion_listado = ($tema == "Oportunidades de Negocios") ? ' GROUP BY n.tamimg ' : ' GROUP BY n.region, n.tamimg';
$condicion_listado = ($SEARCH) ? ' ' : $condicion_listado;
$condicion_listado = ($busqueda_fecha) ? ' ' : $condicion_listado;
if (isset($_REQUEST['category']) && intval($_REQUEST['category']) > 0) {
    $condicion_id_categoria = " and c.id = " . $_REQUEST['category'];
    $categoria = $_REQUEST['category'];
    $condicion_listado = '';
}


$posicion_listado = 0;

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
            n.tamimg as id_categoria,
            n.destacado as destacado
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
        $destacado = $fila->destacado;
        $destacadoImg = $destacado ? "<img src='imagenes/destacado.png' border='0' /> " : "";
        $posicion_listado = 1;
        $listado_paises.="<tr><td colspan='2' width='" . intval(intval($WIDTH_CLIP) * 2) . "' valign='top'>
                        <table border='0' cellpadding='1' cellspacing='0'>
                ";
        $listado_paises.="<tr><td><table border='0' width='" . intval(intval($WIDTH_CLIP) * 2) . "'>
                <tr><td align='left'><a class='volver' href='javascript:history.back();'>Volver</a></td><td align='right' class='fecha_nota_big'>Por <span class='medio'>$fila->medio</span> el " . $fila->fecha . "</td></tr></table></td></tr>";

        if (strlen($fila->volanta)) {
            $listado_paises.="<tr><td align='left' class='cop_nota_big'>" . nl2br($fila->volanta) . "</td></tr>";
        }
        $listado_paises.="<tr><td align='left' class='tit_nota_big'>$destacadoImg" . nl2br($fila->titulo) . "</td></tr>";
        if (strlen($fila->copete)) {
            $listado_paises.="<tr><td align='left' class='vol_nota_big'>" . nl2br($fila->copete) . "</td></tr>";
        }

        $listado_paises.="<tr><td><hr size=1 noshade='noshade' color='#cccccc' /></td></tr>
                    <tr><td align='left' class='txt_nota_big'>" . nl2br($fila->texto) . "<br/><a class='volver' href='javascript:history.back();'>Volver</a></td></tr>
                        </table>
                    </td></tr>";
    } else {
        $ID_NOTA = null;
    }
}
if ($reemplazar) {
    //  $listado_paises = str_replace($STR_SEARCH, "<strong>$STR_SEARCH;
}
$orden_oportunidades = "";
$condicion_posicion2 = " and o.posicion = 0 ";
$condicion_posicion = ($ID_NOTA) ? " " : " and o.posicion = 1 ";
if ($SEARCH || $tema == "Oportunidades de Negocios") {
    //$condicion_listado = " and o.id = 1 ";
    $orden_oportunidades = " c.nombre DESC, ";
    $condicion_posicion2 = "";
}
$condicion_categoria = ($tema == 'Oportunidades de Negocios') ? "o.orden = n.tamimg and " : "n.region = o.region and ";


$query = "  SELECT  DATE_FORMAT(n.fecha_nota,'%e'),
                    DATE_FORMAT(n.fecha_nota,'%c'),
                    DATE_FORMAT(n.fecha_nota,'%Y'),
                    n.titulo as titulo, n.volanta as volanta, n.copete as copete,
                    f.nombre, n.id as id, n.tamimg as tamimg, c.id as id_cat, c.nombre as catnombre, n.destacado as destacado,
                    n.region as region, o.posicion as posicion, c.nombre as categoria, o.orden as orden
            FROM    tautores f, torden o, tnotas n LEFT JOIN tcategorias c ON n.tamimg = c.id
            WHERE   $condicion_categoria
                    $condicion_tema
                    $condicion_buscar
                    $condicion_id_categoria
                    $condicion_posicion2
                    and f.id = n.fuente
                    and n.estado = 1
                    and ( n.fecha_nota >= '$fecha_inicial' and n.fecha_nota <= '$fecha_tope')
                    $condicion_listado
            ORDER BY $orden_oportunidades n.region DESC,
                    n.id DESC, destacado DESC;";
$queryReg = "  SELECT  DATE_FORMAT(n.fecha_nota,'%e'),
                    DATE_FORMAT(n.fecha_nota,'%c'),
                    DATE_FORMAT(n.fecha_nota,'%Y'),
                    n.titulo as titulo, n.volanta as volanta, n.copete as copete,
                    f.nombre, n.id as id, n.tamimg as tamimg, c.id as id_cat, c.nombre as catnombre, n.destacado as destacado,
                    n.region as region, o.posicion as posicion, c.nombre as categoria, o.orden as orden
            FROM    tautores f, torden o, tnotas n LEFT JOIN tcategorias c ON n.tamimg = c.id
            WHERE   $condicion_categoria
                    $condicion_tema
                    $condicion_buscar
                    $condicion_id_categoria
                    $condicion_posicion
                    and f.id = n.fuente
                    and n.estado = 1
                    and ( n.fecha_nota >= '$fecha_inicial' and n.fecha_nota <= '$fecha_tope')
                    $condicion_listado
            ORDER BY $orden_oportunidades n.region DESC,
                    n.id DESC, destacado DESC;";

//n.id DESC;";
//o.orden ASC
$COLS = 2;
if ($tema == 'Oportunidades de Negocios') {

    //$WIDTH_CLIP = intval($WIDTH_CLIP) * 2;
    //$COLS = 1;
}
//Listar noticias de entre begin y end
$mdb = new consulta($query);
$mdb2 = new consulta($queryReg);
echo "<!--$query.-->";
echo "<!--$queryReg.-->";
$i = 2;
$antOrder = 999;
if (!$ID_NOTA && $mdb->cant) {
    while ($r = mysql_fetch_object($mdb->result)) {
        if ((($tema == 'FAETT' or $tema == 'CIETT' or $tema == 'work') and $antOrder !== $r->orden)
                or ($tema !== 'FAETT' and $tema !== 'work' and $tema !== 'CIETT')) {
            $antOrder = $r->orden;

            $destacado = intval($r->destacado);
            $destacadoImg = $destacado ? "<img src='imagenes/destacado.png' border='0' /> " : "";
            if ($posicion_listado == 0 && $r->posicion == 0 or $tema == 'Oportunidades de Negocios') {
                $if_tr = ($i % $COLS == 0) ? "<tr>" : "";
                $listado_paises.=$if_tr;
                $listado_paises.="
            <td width='$WIDTH_CLIP' valign='top'>
                <table border='0' cellpadding='1'  cellspacing='3'>";
                if ($tema !== 'Oportunidades de Negocios') {
                    $listado_paises.="<tr><td align='left'   class='tit_nota'>" . $r->region . "</td></tr>";
                    $listado_paises.="<tr><td align='left' bgcolor='#FFFFFF' style='color: #555555; font-size:11px; padding:2px;padding-left: 8px;'>" . str_ireplace($STR_SEARCH, "<span style='color: #f43996; font-size:13px;'><strong>$STR_SEARCH</strong></span>", $r->volanta) . "</td></tr>";
                } else {
                    if ((!isset($arr_title[$r->id_cat]) or $arr_title[$r->id_cat] == 0) && (!isset($_REQUEST['category']))) {
                        $arr_title[$r->id_cat] = 1;
                        $listado_paises.="<tr><td align='left'   class='tit_nota'>" . $arr_sectores[$r->id_cat] . "</td></tr>";
                        //$listado_paises.="<tr><td align='left' bgcolor='#DDDDDD' 
                        //style='color: #555555; font-size:11px; padding:2px;padding-left: 8px;'><i>" . $arr_sectores[$r->id_cat] . "
                        //</i></td></tr>";
                    }
                }
                $listado_paises.="<tr><td align='left' width='$WIDTH_CLIP' class='vol_nota'>$destacadoImg
                <a style='color: #333333;font-size:10px; font-weight:bold;' 
                href='$URL?tema=$tema&fecha=$fecha_tope&id_nota=$r->id'>"
                        . str_ireplace($STR_SEARCH, "<span style='color: #f43996; font-size:13px;'><strong>$STR_SEARCH</strong></span>", nl2br($r->titulo)) . "</a></td></tr>
                    <tr><td align='left' class='txt_nota'>" . str_ireplace($STR_SEARCH, "<span style='color: #f43996; font-size:13px;'><strong>$STR_SEARCH</strong></span>", nl2br($r->copete)) . "</td></tr>
                    <tr><td align='right'>
                       <a class='txt_link' href='$URL?tema=$tema&fecha=$fecha_tope&id_nota=$r->id'>
                        Ver m&aacute;s &gt;</a></td></tr>
                </table>
            </td>";
                $i++;
                $if_tr = ($i % $COLS == 0) ? "</tr>" : "";
                $listado_paises.=$if_tr;
            }
            /*
              else {

              if ($if_tr != "</tr>") {
              if ($i == 2)
              $listado_paises.="<td width='$WIDTH_CLIP'></td>";
              $listado_paises.="<td width='$WIDTH_CLIP'></td></tr>";
              $if_tr = "</tr>";
              }
              $listado_conti.="
              <tr><td align='left' valign='top' >
              <table border='0' class='fnd_conti' width='133' cellpadding='1' cellspacing='0'>";
              if ($posicion_listado == 0)
              $listado_conti.="<tr><td align='left' class='tit_nota_conti'>" . $r->region . "</td></tr>";

              $listado_conti.="<tr><td align='left' class='vol_nota'>$destacadoImg<a style='color: #333333;font-size:11px; font-weight:bold;' href='$URL?fecha=$fecha_tope&tema=$tema&id_nota=$r->id'>" . nl2br($r->titulo) . "</a></td></tr>";
              $listado_conti.="<tr><td align='left' class='txt_nota'>" . nl2br($r->copete) . "</td></tr>
              <tr><td align='right'><a class='txt_link' href='$URL?tema=$tema&fecha=$fecha_tope&id_nota=$r->id'>Ver m&aacute;s ";
              $listado_conti.=(empty($ID_NOTA)) ? "noticias &gt;" : "";
              $listado_conti.="</a></td></tr>
              </table>
              </td></tr>";

              } */
        }
    }
    if ($if_tr != "</tr>") {
        $listado_paises.="<td width='$WIDTH_CLIP'></td></tr>";
        $if_tr = "</tr>";
    }
} else {
    if (!$ID_NOTA)
        $listado_paises.="
            <td colspan=2 width='" . intval(2 * intval($WIDTH_CLIP)) . "' valign='top' class=msg></td>";
}



$i = 2;
$antOrder = 999;
if ($mdb2->cant) {
    while ($r = mysql_fetch_object($mdb2->result)) {
        if ((($tema == 'FAETT' or $tema == 'CLETT&A' or $tema == 'CIETT' or $tema == 'work') and $antOrder !== $r->orden)
                or ($tema !== 'FAETT' and $tema !== 'CLETT&A' and $tema !== 'work' and $tema !== 'CIETT')) {
            $antOrder = $r->orden;

            $destacado = intval($r->destacado);
            $destacadoImg = $destacado ? "<img src='imagenes/destacado.png' border='0' /> " : "";
            $listado_conti.="
                <tr><td align='left' valign='top' >
                    <table border='0' class='fnd_conti' width='158' cellpadding='1' cellspacing='0'>";
            if ($posicion_listado == 0)
                $listado_conti.="<tr><td align='left' class='tit_nota_conti'>" . $r->region . "</td></tr>";

            $listado_conti.="<tr><td align='left' class='vol_nota'>$destacadoImg<a style='color: #333333;font-size:10px; font-weight:bold;' href='$URL?fecha=$fecha_tope&tema=$tema&id_nota=$r->id'>" . str_ireplace($STR_SEARCH, "<span style='color: #f43996; font-size:13px;'><strong>$STR_SEARCH</strong></span>", nl2br($r->titulo)) . "</a></td></tr>";
            $listado_conti.="<tr><td align='left' class='txt_nota'>" . str_ireplace($STR_SEARCH, "<span style='color: #f43996; font-size:13px;'><strong>$STR_SEARCH</strong></span>", nl2br($r->copete)) . "</td></tr>
                        <tr><td align='right'><a class='txt_link' href='$URL?tema=$tema&fecha=$fecha_tope&id_nota=$r->id'>Ver m&aacute;s ";
            $listado_conti.=(empty($ID_NOTA)) ? "noticias &gt;" : "";
            $listado_conti.="</a></td></tr>
                    </table>
                </td></tr>";
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
            <tr><td align='left' class='td_tit_historial'><a href='$URL?fecha=$fecha_usada&tema=$tema&category=$categoria'>" . $str_fecha . "</a></td></tr> ";
        $c++;
    }
    $i++;
}
/*
 *  Buscador
 */




$CLETT = 0;
$CIETT = 0;
$FAETT = 0;
$WORK = 0;
$opts = new consulta("SELECT * FROM topciones");
while ($ss = mysql_fetch_array($opts->result)) {
    $CIETT = intval($ss[0]);
    $CLETT = intval($ss[3]);
    $FAETT = intval($ss[1]);
    $WORK = intval($ss[2]);
}




$nowYear = date('Y', time());
$nowMonth = intval(date('m', time()) - 1);
$nowDay = date('d', time());
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

$visit = new consulta("INSERT INTO visitas VALUES(
    null,
    NOW(),
    '$tema',
    '" . $_REQUEST['fecha'] . "',
    '" . $_REQUEST['search'] . "',
    '" . $_SERVER['REMOTE_ADDR'] . "',
    '" . $_SERVER['HTTP_USER_AGENT'] . "',
        '" . $_SERVER['QUERY_STRING'] . "'
);")
?> 
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
        <link rel="stylesheet" href="<?php echo $URL; ?>style.css" type="text/css">
        <link rel="alternate" type="application/rss+xml" title="RSS .92" href="http://www.clipdenoticias.com/manpower/rss/index.php" />
        <link rel="stylesheet" media="screen" type="text/css" href="datepicker.css?time=<?php echo microtime(); ?>" />
        <script src="../funciones.js" type="text/javascript"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
        <script type="text/javascript" src="datepicker.js?t=<?php echo time(); ?>"></script>
        <style type="text/css">
            body {margin: 0 auto 0 auto; font-family: Verdana; font-size: 12px; color: #333333;}
            a {text-decoration: none; color: #333333; font-weight: normal;}
            input {border: solid 1px #cccccc; width:80px; height:20px; padding:2px; font-size: 11px;}
            .fecha_nota_big {font-size: 11px; color: #777777;}
            .medio {color:firebrick }
            .descBuscar {font-size: 11px; color: #555555;}
            .menu_td{font-family:Verdana; text-align:center; padding: 2px; margin:1px; font-size:11px;

                     -moz-border-radius:3px 3px 3px 3px;
                     border-radius:3px 3px 3px 3px;
                     -webkit-border-radius:3px 3px 3px 3px;
            }
            .msg {font-size: 11px; color: #333333; text-align:center;padding-top:20px}
            .barra_de_menu {color: #222222; text-decoration: none;}
            .td_paises {background:url("<?php echo $URL; ?>imagenes/newsletter_03<?php if (!$MOSTRAR_SECTORES) echo "_2"; ?>.gif");padding: 0px; padding-left:10px; color: #FFFFFF; font-size: 12px;}
            .td_continentes {background:url("<?php echo $URL; ?>imagenes/newsletter_04.gif"); padding: 0px; padding-left:10px;  color: #FFFFFF; font-size: 12px;}
            .td_continentes2 {background:url("<?php echo $URL; ?>imagenes/newsletter_04_2.gif"); padding: 0px; padding-left:10px;  color: #FFFFFF; font-size: 12px;}
            .td_sectores {background:url("<?php echo $URL; ?>imagenes/newsletter_05.gif"); padding: 0px; padding-left:10px;  color: #FFFFFF; font-size: 12px;}
            .td_historial {background:url("<?php echo $URL; ?>imagenes/newsletter_06.gif"); padding: 0px; padding-left:10px;  color: #FFFFFF; font-size: 12px;}
            .td_tit_historial {padding: 4px; border-bottom: solid 1px #FFFFFF; padding-left: 8px; font-size:9px; font-weight: bold; background:url("<?php echo $URL; ?>imagenes/fondo_historial.gif"); color:#444444;}
            .td_tit_sectores {padding: 8px; border-bottom: solid 1px #BBBBBB; padding-left: 8px; font-size:11px; background:url("<?php echo $URL; ?>imagenes/fondo_sector.gif") repeat-y; color:#333333;}
            .sin_linea {border: 0px;}
            /*.fnd_conti {background:url("<?php echo $URL; ?>imagenes/newsletter-24.gif") repeat-y;}*/
            /*.tit_nota_conti {padding-left: 8px; font-size:12px; background:url("<?php echo $URL; ?>imagenes/newsletter_22.gif"); color:#333333; font-style: italic; font-weight: normal;}
            */
            .tit_nota_conti,.tit_nota {padding-left: 8px; font-size:12px; font-weight:bold; color:#666; font-weight: bold;padding-bottom:5px;}
            .tit_nota_conti {height: 28px;font-size:11px;margin-bottom:4px;padding-top:1px;background: url('./regiones.nuevo.1.png?t=<?php echo time(); ?>');}

            .tit_nota {border-bottom:solid 1px #ccc;}
            /*.tit_nota {padding-left: 8px; font-size:12px; background-color:#D7E8F0; color:#3E8BB7; font-style: italic; font-weight: bold;}*/
            .tit_nota_big {font-size: 16px; color: #3E8BB7; padding: 4px;}
            .vol_nota_big {font-size: 14px; color: #666666; padding: 4px;}
            .cop_nota_big {font-size: 12px; color: #666666; padding: 4px;}
            .txt_nota_big {font-size: 11px; color: #666666; padding: 4px;}
            .vol_nota {padding-left: 8px; font-size:9px; font-weight: bold;}
            .txt_nota { padding-left: 8px; font-size:10px;}
            .txt_link {padding-right: 2px; font-size:10px; font-weight:600; text-align:right;color: #F99D38; }
            .footer {font-size:11px;}
            .volver {font-size:11px; color: #3E8BB7;}
        </style>
        <script language="javascript">
            $(document).ready(function()
            {  
                
                var now = new Date(<?php echo $nowYear . ", " . $nowMonth . ", " . $nowDay; ?>); 
                var now2 = new Date(<?php echo $currentYear . "," . intval($currentMonth - 1) . "," . $currentDay; ?>);
                now2.setHours(0,0,0,0);
                now.setHours(0,0,0,0);
                //alert(now2 +" - "+now);
                $('#date').DatePicker({
                    flat: true,
                    date: ['<?php echo $currentDate; ?>'],
                    current: '<?php echo $currentDate; ?>',
                    format: 'Y-m-d',
                    calendars: 1,
                    changeYear: false,
                    yearRange: '2012:2013',
                    mode: 'single', 
                    onRender: function(date) { 
                        return {
                            disabled: (date.valueOf() > now.valueOf()),
                            className: date.valueOf() == now2.valueOf() ? 'datepickerSpecial' : false
                        }
                    },
                    starts: 0
                });
                 
            });
        </script> 
    </head>

    <body bgcolor="#ffffff">
<?php echo "<input type='hidden' id='codTema' value='$tema' />"; ?>
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
                                    <div 
                                        style="z-index:101;float:right;padding-right:20px;font-family:Verdana; color: black;padding-top:2px; font-size:10px;font-weight:bold;margin-top:-30px;">
                                        Del <?php
if (date('d', strtotime($fecha_inicial)) > date('d', strtotime($fecha_tope))) {
    echo date('j ', strtotime($fecha_inicial)) . ' de ' . $month[date('m ', strtotime($fecha_inicial)) - 1];
} else {
    echo date('j', strtotime($fecha_inicial));
}
echo " al " . date('j', strtotime($fecha_tope)) . ' de ' . $month[date('m ', strtotime($fecha_tope)) - 1] . ".";
?>
                                    </div> 
                                </td>
                            </tr>

                            <!-- MENU 1 -->
                            <tr>
                                <td height="18"  align="center" valign="middle">
                                    <table width="710" border="0" cellspacing="2" cellpadding="0"><tr>
                                            <td class="menu_td" bgcolor="<?php echo ($tema == 'Manpower') ? "#FCB040" : "#E6E6E6"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=Manpower&BuscarFecha=Buscar&page=1">MANPOWER</a></td>
                                            <?php
                                            if ($FAETT) {
                                                ?>
                                                <td class="menu_td"  bgcolor="<?php echo ($tema == 'FAETT') ? "#FCB040" : "#E3E0E0"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=FAETT&BuscarFecha=Buscar&page=1">FAETT</a></td>
                                                <?php
                                            } elseif ($WORK) {
                                                ?>
                                                <td class="menu_td"  bgcolor="<?php echo ($tema == 'work') ? "#FCB040" : "#E7E7E7"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=work&BuscarFecha=Buscar&page=1">Empleo Temporario</a></td>
                                                <?php
                                            }
                                            if ($CIETT) {
                                                ?>
                                                <td class="menu_td"  bgcolor="<?php echo ($tema == 'CIETT') ? "#FCB040" : "#E3E0E0"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=CIETT&BuscarFecha=Buscar&page=1">CIETT</a></td>
                                                <td class="menu_td"  bgcolor="<?php echo ($tema == 'Competencia Manpower') ? "#FCB040" : "#DDDDDD"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=Competencia Manpower&BuscarFecha=Buscar&page=1">Competencia</a></td>
                                                <?php
                                            } else {

                                                if ($CLETT) {
                                                    ?>



                                                    <td class="menu_td"  bgcolor="<?php echo ($tema == 'CLETT&A') ? "#FCB040" : "#E3E0E0"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=CLETT%26A&BuscarFecha=Buscar&page=1">CLETT&amp;A</a></td>
                                                    <td class="menu_td"  bgcolor="<?php echo ($tema == 'Competencia Manpower') ? "#FCB040" : "#DDDDDD"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=Competencia Manpower&BuscarFecha=Buscar&page=1">Competencia</a></td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td class="menu_td"  bgcolor="<?php echo ($tema == 'Competencia Manpower') ? "#FCB040" : "#DDDDDD"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=Competencia Manpower&BuscarFecha=Buscar&page=1">Competencia</a></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <td class="menu_td"  bgcolor="<?php echo ($tema == 'Recursos Humanos') ? "#FCB040" : "#D8D4D4"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=Recursos Humanos&BuscarFecha=Buscar&page=1">Mercado Laboral</a></td>
                                            <td class="menu_td"  bgcolor="<?php echo ($tema == 'nom') ? "#FCB040" : "#CCCCCC"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=nom&BuscarFecha=Buscar&page=1">Nombramientos</a></td>
                                            <td class="menu_td"  bgcolor="<?php echo ($tema == 'Oportunidades de Negocios') ? "#FCB040" : "#BFBFBF"; ?>"><a class="barra_de_menu" href="<?php echo $URL; ?>?tema=Oportunidades de Negocios&BuscarFecha=Buscar&page=1">Oportunidades de Negocios</a></td>
                                        </tr></table>
                                </td>
                            </tr>
                            <!-- MENU 2 -->
                            <tr>
                                <td style="padding-top:4px; padding-bottom:4px;">
<?php
if ($tema == 'Oportunidades de Negocios') {
    ?><img src="./menupaises222.jpg" width="710" border="0" /><?php
                                } else {
    ?><img src="./menupaises22.jpg" width="710" border="0" /><?php }
?> 
                                    <!--<table  width="710" border="0" cellpadding="0" align="left" cellspacing="0">
                                        <tr>
                                            <td style="border-right: solid 0px #000;left: -100"
                                                width="<?php if (!$MOSTRAR_SECTORES) echo "460"; else echo "418"; ?>"
                                                height="24" class="td_paises">
<?php
if ($ID_NOTA) {
    echo "Seccion: $temaString";
} else {
    if ($tema == 'SECTORES')
        echo $tema;
    else
        echo "PAISES";
}
?></td>
                                    <?php if ($MOSTRAR_SECTORES) { ?>
                                                                                <td width="171" height="24" class="td_sectores">SECTORES</td>
                                    <?php } else { ?>
                                                                                <td width="132" height="24" class="td_continentes2"><?php
                                    if (empty($ID_NOTA)) {
                                        echo "REGIONES";
                                    }
                                    ?></td>
                                    <?php } ?>
                                            <td width="92" height="24" class="td_historial">HISTORIAL</td>
                                        </tr>
                                    </table>-->
                                </td>
                            </tr>
                            <tr><td   align="left" valign="top">
                                    <table border="0" cellpadding="0" width="710" cellspacing="0"><tr><td valign="top">
                                                <table  style="border:solid 1px #CCCCCC;"  cellspacing="0" cellpadding="0">
                                                    <tr><td valign="top" align="left" >
                                                            <table border="0" cellpadding="0" cellspacing="0"><?php echo $listado_paises; ?>
                                                            </table> 
                                                        </td>
                                                        <!-- CONTINENTES -->
<?php if (!$MOSTRAR_SECTORES) { ?>
                                                            <td valign="top" width="160" align="left">
                                                                <table  align="right" width="160" cellpadding="0" cellspacing="0" border="0">
                                                            <?php echo $listado_conti; ?>

                                                                </table>
                                                            </td>
                                                                <?php } ?>
<?php
//$MOSTRAR_SECTORES = 0;
if ($MOSTRAR_SECTORES) {
    ?>
                                                            <!-- SECTORES -->
                                                            <td valign="top" width="160" align="right">
                                                                <table width="160" align="right" cellpadding="1" cellspacing="0" border="0">
                                                            <?php echo $listado_sectores; ?>
                                                                </table>
                                                            </td>
                                                                <?php } ?>
                                                    </tr>
                                                </table>
                                            </td><!-- HISTORIAL -->
                                            <td valign="top" align="center" style="text-align:center; padding:0px;background:none;width:94px;">
                                                <div class="busqueda_title">Busqueda de notas</div> 
                                                <div id="date" style="background: url('./fondo_calendario.gif')"></div>
                                                <div style="height:12px;"></div>
                                                <div class="descBuscar" style="text-align:left">&gt; Buscar</div>
                                                <div style="padding:0px;padding-left:6px;margin:0px;text-align:left;">
                                                    <input  type="text" name="search" id="txtBuscar" value="" style="width:92px;height:22px;vertical-align:top;"  /> 
                                                    <img src="imagenes/buscar.gif" onclick="document.forms[0].submit()" border="0" style="vertical-align:bottom;cursor:pointer;" />
                                                </div>

                                            </td>
                                        </tr>

                                    </table></td></tr>
                            <!-- FOOTER -->

                        </table>
                    </td></tr>
            </table> 
        </form> 
    </body>
</html>
