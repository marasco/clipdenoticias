<?php
$export = false;
if (!empty($_POST['since']) && !empty($_POST['until'])){
    $export = true;
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=export_cadit.doc");
}
function texto($a) {
    $a = str_replace("<", "&lt;", $a);
    $a = str_replace(">", "&gt;", $a);
    $a = str_replace("'", "&quot;", $a);
    $a = str_replace(chr(34), "&quot;", $a);
    return $a;
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
if ($export){
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
$filtro_fecha_hoy = " and n.fecha_nota = CURRENT_DATE() ";
$dia_h = date("D");
//echo $dia_h."<br>";
switch ($dia_h) {
    case "Sun": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-2 ";
    case "Sat": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-1 ";
    case "Mon": $filtro_dia_hoy = " and ( n.fecha_nota = CURRENT_DATE()-2 or n.fecha_nota = CURRENT_DATE()-1 or n.fecha_nota = CURRENT_DATE() )  ";
}
$suscripcion = 0; //Si es 0 no esta suscripto o por fecha o por estado, si es 1 si
$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);
$tema = null;
if (!empty($_GET['tema'])){
    $tema = $_GET['tema'];
}
$getema = "&tema=" . $tema;
$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);
$sinceDate = $_POST['since'];
$untilDate = $_POST['until'];
    if ( !empty($sinceDate )) {
        $query = "
        SELECT DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'), DATE_FORMAT(n.fecha_nota,'%y'),
        n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region, n.codigo_tema, n.link  
FROM tnotas n, tautores f
WHERE (n.codigo_tema = 'cadit' OR n.codigo_tema = 'ministerio' OR n.codigo_tema = 'PREPAGAS' OR n.codigo_tema = 'SECTOR' OR n.codigo_tema = 'ANMAT')
AND f.id = n.fuente AND n.estado = 1 AND n.fecha_nota >= '" . $sinceDate . "' AND n.fecha_nota <='".$untilDate."' 
ORDER BY  FIELD(n.codigo_tema, 'cadit', 'sector','ministerio', 'prepagas','anmat'), n.fecha_nota DESC ";
    if (isset($_GET['test'])){
        die($query);
    }
}
$con = mysql_query($query, $db);
$i = 0;
$inicio = 0;
$actual = 1;
if ($_GET['page'] != "")
    $actual = $_GET['page'];
$inicio = ($actual * $CPP) - $CPP;
$fin = $inicio + $CPP;
$tit5 = 0;
$tit1 = 0;
$tit2 = 0;
$tit3 = 0;
$tit4 = 0;
$tit6 = 0;
$tit7 = 0;
$tit8 = 0;
$titedu1 = 0;
$titedu2 = 0;
while ($rs = mysql_fetch_array($con)) {
        $titulo  = utf8_encode($rs[3]);
        $volanta = utf8_encode($rs[4]);
        $resumen = nl2br(utf8_encode($rs[5]));
        $texto   = nl2br(strip_tags(htmlentities((utf8_encode(str_replace(array("\"","\“","“", "&quot;", chr(39), "'"), "-", $rs[6]))))));

        $texto = html_entity_decode($texto);
        $fecha   = utf8_encode($rs[0] . " de " . $lista_meses[$rs[1] - 1] . " de " . $rs[2]);
        $autor   = utf8_encode($rs[7]);
        $idn     = $rs[8];
        $tamimg  = $rs[9];
        $link    = $rs[12];
        $codigo_tema  = strtoupper($rs[11]);

        switch (strtoupper($codigo_tema)) {
               case 'ANMAT':
               $titulo_barra = "Disposiciones Anmat";
               break;
               case 'MINISTERIO':
                $titulo_barra = "Ministerio de Salud";
               break;
               case 'PREPAGAS':
                $titulo_barra = "Obras sociales y Prepagas";
               break;
               case 'SECTOR':
                $titulo_barra = "Noticias del Sector";
               break;
               default:
                    $titulo_barra = 'CADIT';
               break;
           }   
    


        $listado_notas.=
                "<p><strong>{$titulo_barra}</strong> - {$volanta}</p>
                <p><h1>" . $titulo . "</h1></p>
                <p><h2>{$resumen}</h2></p>
                <p>" . $texto . "</p>
                <p>
                <span class='fuente'><strong>Fuente:
                <span class='autor'>" . $autor . "</span>
                <span class='fecha'>, " . $fecha . "</span>
                </strong>
                
                ";
                if (!empty($link)){
                    $listado_notas.= "<br />Link: <a href='".$link."'>".$link."</a> ";
                } 
                $listado_notas .="
                </span>
                </p><hr size='1' />";
}
$tipo_susc = $i;
mysql_close($db);
$total = intval($i / $CPP);
if ($_GET['btnBuscar'] == "Buscar") {
    $CADENA_BUSCAR = "&btnBuscar=Buscar&search=" . $_GET['search'] . "&p=p";
}
if ($_GET['BuscarFecha'] == "Buscar") {
    $CADENA_BUSCAR.= "&BuscarFecha=Buscar&fdia=" . $_GET['fdia'] . "&fmes=" . $_GET['fmes'] . "&fanio=" . $_GET['fanio'];
}
$resto = 0;
if (($i % $CPP) > 0)
    $resto = 1;
$total+=$resto;
if ($total == 0)
    $total = 1;
$pie_pagina = "P&aacute;gina " . $actual . " de " . $total . " ";
$pie_pagina.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
$inicial = (intval($_GET['page']) > 5) ? intval($_GET['page']) - 5 : 1;
$final = ($total > $inicial + 10) ? $inicial + 10 : $total;
$page_anterior = (intval($_GET['page']) > 10) ? (intval($_GET['page']) - 10) : 1;
$pie_pagina.=$inicial > 1 ? "<a class='texto' href='?page=$page_anterior$getema$CADENA_BUSCAR'>&lt;&lt; </a> " : "";
if (1 < $actual)
    $pie_pagina.="<a class='texto' href='?page=" . intval($actual - 1) . "$getema$CADENA_BUSCAR'>Anterior </a> - ";
for ($i = $inicial; $i <= $final; $i++) {
    if ($i == $actual)
        $pie_pagina.="<a class='lnk_pagina_actual' href='?page=" . $i . $getema . $CADENA_BUSCAR . "'>" . $i . "</a> ";
    else
        $pie_pagina.="<a class='texto' href='?page=" . $i . $getema . $CADENA_BUSCAR . "'>" . $i . "</a> ";
}
if ($actual < $total){
    $pie_pagina.="- <a class='texto' href='?page=" . intval($actual + 1) . "$getema$CADENA_BUSCAR'>Siguiente </a>";
}
$page_posterior = (intval($_GET['page']) < $total - 10) ? (intval($_GET['page']) + 10) : $total;
$pie_pagina.=$final < $total - 10 ? " <a class='texto' href='?page=$page_posterior$getema$CADENA_BUSCAR'> &gt;&gt;</a>" : "";
?>
<html>
    <?php
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
    ?>
  <img width="700" src="http://clipdenoticias.com/cadit/logo.jpg" border="0" />
   <?php
            echo $listado_notas;
        }else{
    ?>
    <html><header>
        <link  rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="bootstrap-datepicker3.min.css">
        <link type="text/css" rel="stylesheet" src="style.css" >
            <script type="text/javascript" src="jquery-1.11.0.min.js" ></script>
            <script type="text/javascript" src="bootstrap.min.js" ></script>
            <script type="text/javascript" src="bootstrap-datepicker.js" ></script>
            <script type="text/javascript">
                            $(function(){
                                $('.c').datepicker({
                                    'autoclose':true,
                                    'format':'yyyy-mm-dd' });
                            })
            </script>
        </header>
        <body>
            <div class="container" align="center" style="padding:20px;">
                <div class="logo">
                    <img width="700" src="http://clipdenoticias.com/cadit/logo.jpg" border="0" />
                </div>
                <form method='POST'>
                    <div style="font-family: Verdana,serif">
                        <h1>Exportar noticias</h1>
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-2">
                                <div class="col-xs-6">
                                    <label>Desde</label>
                                </div>
                                <div class="col-xs-6">
                                    <label>Hasta</label>
                                </div>
                                <div class="col-xs-6">

                                    <input type="text"  class="c form-control"  name="since" />
                                </div>
                                <div class="col-xs-6">
                                    <input  type="text" class="c form-control" name="until" />
                                </div>
                                <div class="col-xs-12 " style="margin-top:20px">
                                    <button type="submit" value="Exportar">Exportar</button>
                                </div>
                            </div></div>
                        </form>
                    </div>
                </div>
            </body>
        </html>
        <?php
                }
        ?>