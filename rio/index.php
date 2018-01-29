<?php
error_reporting(E_ERROR);
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

    ;

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
$filtro_fecha_hoy = ""; // and n.fecha_nota = CURRENT_DATE() ";
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
$suscripcion = 0; //Si es 0 no esta suscripto o por fecha o por estado, si es 1 si
$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);

$tema = null;

if (!empty($_GET['tema'])){
    $tema = $_GET['tema'];
}
if ($hojaestilo == "default") {
    $hojaestilo = "";
} else {
    $hojaestilo = "<link rel='stylesheet' href='./rio.css?t=".time()."' type='text/css'>";
}

//Paginacion
$getema = "&tema=" . $tema;


//Listar noticias de entre begin y end

$desc_tema = array('rio'=>"rio");


$db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
mysql_select_db("clipping", $db);
    $orderx = " FIELD(n.codigo_tema, 'rio'), ";

if (  !empty($_GET['search'])) {
    
    $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region, n.codigo_tema, n.link
    from tnotas n, tautores f where 
    ( n.codigo_tema = 'rio') " . $fecha_inicial_s . " and f.id = n.fuente  and n.estado = 1 and ( n.titulo like '%" . $_GET['search'] . "%' or n.copete like '%" . $_GET['search'] . "%' ) order by $orderx n.region asc, id desc";
    $titulo_barra = "Resultados de la b&uacute;squeda";
} else {

    if (($_GET['BuscarFecha'] == "Buscar") && ($_GET['fdia'] != "") && ($_GET['fmes'] != "") && ($_GET['fanio'] != "")) {
                    
        $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region, n.codigo_tema, n.link
        from tnotas n, tautores f where 
        ( n.codigo_tema = 'rio')   " . $fecha_inicial_s . " and f.id = n.fuente and n.estado = 1 and n.fecha_nota = '" . $_GET['fanio'] . "-" . $_GET['fmes'] . "-" . $_GET['fdia'] . "' order by $orderx  n.region asc,  id desc";
        $titulo_barra = "Notas del " . $_GET['fdia'] . "/" . $_GET['fmes'] . "/" . $_GET['fanio'];
       
    } elseif (empty($tema)) {
        $dow = date('D', time());

        

        $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region, n.codigo_tema, n.link
        from tnotas n, tautores f where 
        ( n.codigo_tema = 'rio')  " . $fecha_inicial_s . " and f.id = n.fuente and n.estado = 1 " . $filtro_fecha_hoy . " order by $orderx n.region asc, n.id desc";
     }else{
        $dow = date('D', time());

        $orderx = " n.codigo_tema ASC,";

        $query = "select DATE_FORMAT(n.fecha_nota,'%e'), DATE_FORMAT(n.fecha_nota,'%c'),DATE_FORMAT(n.fecha_nota,'%y'), n.titulo, n.volanta, n.copete, n.texto, f.nombre, n.id, n.tamimg, n.region, n.codigo_tema, n.link
        from tnotas n, tautores f where 
        (n.codigo_tema = '".$tema."') " . $fecha_inicial_s . " and f.id = n.fuente and n.estado = 1 " . $filtro_fecha_hoy . " order by $orderx n.region asc, n.id desc";
        

        $titulo_barra = $desc_tema[strtoupper($tema)]; 
 }
}
echo "<!-- $query -->";
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
$tema_anterior = null;
while ($rs = mysql_fetch_array($con)) {
    if (($i >= $inicio) && ($i < $fin)) {
        $titulo = $rs[3];
        $volanta = $rs[4];
        $resumen = nl2br($rs[5]);
        $fecha = $rs[0] . " de " . $lista_meses[$rs[1] - 1] . " de " . $rs[2];
        $autor = $rs[7];
        $idn = $rs[8];
        $tamimg = $rs[9];
        $cod_tema = $rs[11];
        $link = $rs[12];
        if ( $cod_tema != $tema_anterior ) {
            $listado_notas.= "<tr><td height='20' bgcolor='#006666' class='TITULO_CELESTE'>&nbsp;&nbsp;".$desc_tema[strtoupper($cod_tema)]."</td></tr>";
        }
        $tema_anterior = $cod_tema;


        $listado_notas.=
        "<tr><td class='volanta'>

        " . $volanta . "</td></tr>
        <tr><td bgcolor='#FFFFFF' class='titulo'><a href='./vernota.php?id=" . $idn . "' 				class='titulo2'>" . $titulo . "</a></td></tr>
        <tr><td height='1' valign='middle' bgcolor='#cccccc'></td></tr>
        <tr><td class='texto'>" . $resumen . "</td></tr>
        <tr><td align='right' valign='middle'><a href='./vernota.php?id=" . $idn . "' class='vernota'>Ver nota completa</a></td></tr>
        <tr><td height='20' align='right' valign='middle' bgcolor='#ffffff'>
        <span class='fondo_gris'>
        <span class='fuente'>&nbsp;Fuente: <span class='autor'>" . $autor . "</span></span>
        <span class='fecha'>, " . $fecha . "&nbsp;</span>
        </span>
        ";
        if (!empty($link)){
            $listado_notas .="<br /><a target='_blank' class='vernota' href='{$link}'>Ir a la nota original</a>";
        }
        
        $listado_notas .="</td></tr>
        <tr><td height='2' align='right' valign='bottom' bgcolor='#333333'></td></tr>";
    };
    $i++;
}
$tipo_susc = $i;

mysql_close($db);
if ($i < 1)
    $listado_notas.= "<tr><td class='texto'>No se han publicado noticias de acuerdo al criterio solicitado.<br><br><a href='javascript:history.go(-1);' class='vernota'>:: Volver</a></td></tr>";

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
if ($actual < $total)
    $pie_pagina.="- <a class='texto' href='?page=" . intval($actual + 1) . "$getema$CADENA_BUSCAR'>Siguiente </a>";
$page_posterior = (intval($_GET['page']) < $total - 10) ? (intval($_GET['page']) + 10) : $total;
$pie_pagina.=$final < $total - 10 ? " <a class='texto' href='?page=$page_posterior$getema$CADENA_BUSCAR'> &gt;&gt;</a>" : "";

function fecha_diff($data1, $data2) {

    // 86400 seg = 60 [seg/1_minuto] * 60 [1_minuto / 1_hora]* 24 [1_hora]

    $segundos = strtotime($data2) - strtotime($data1);
    $dias = intval($segundos / 86400);
    $sl_retorna = $dias;
    return $sl_retorna;
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
?>


<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <title>RIO PARANA - Clipping de Noticias</title>
    <link rel="stylesheet" href="./main.css?t=<?php echo microtime(); ?>" type="text/css">
    <link rel="stylesheet" media="screen" type="text/css" href="./datepicker.css" />
    <?php echo $hojaestilo; ?>
    <script src="../funciones.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script type="text/javascript" src="./datepicker.js?e=fs"></script>
    <script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
    <script language="javascript">
        function xsubmit(){
            $("#login").submit();
        }
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
    <form name="login" method="GET" id="login" action="index.php">
        <table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
            <tr>
                <td height="24">
                    <table width="680" border="0" align="center" cellpadding="2" cellspacing="0">
                        <tr>
                            <td  colspan="2" align="center" valign="top">
                                <div align="center" style = "text-align:center;background:#FFF; color: #777; font-size:18px; font-family: Helvetica; padding:4px;" >
                                    <div style=""><a href="/rio/" border="0"><img src="./logo.png?x=8282" border="0" /></a></div>
                                    <div style="padding:10px;">Clipping de Noticias</div>
                                    
                                </div>
                                    </td>
                                </tr> 
                     <tr><td colspan="2"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>

                     <tr>
                        <td width="520"  align="center" valign="top">
                            <table width="500" style="border:solid 1px; border-color:#fcfcfc" border="0" cellspacing="6" cellpadding="0">
                                <tr>

                                    <td style="padding:10px;" class="TITULO_BLANCO"><?php echo $titulo_barra; ?></td>

                                </tr>
                                <?php echo $listado_notas; ?>


                                <tr>
                                    <td height="20" align="right" valign="middle" bgcolor="#eeeeee" class="fecha"><?php echo $pie_pagina; ?>&nbsp;</td>
                                </tr>
                            </table>

                        </td>
                        <td width="200" align="left" valign="top"><table width="200" style="border:0px;" border="0" cellspacing="6" cellpadding="0"><? echo $logos; ?>
                            <tr>
                                <td style="padding:10px;" class="TITULO_BLANCO">B&uacute;squeda de notas</td>
                            </tr>

                            <tr>
                                <td bgcolor="#FFFFFF" class="texto_verde"> 
                                    <div id="date"></div>

                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="#FFFFFF" class="texto_verde"><input onClick="limpiar('txtbusqueda');" class="form_textbox_180" type="text" value="" id="txtbusqueda" name="search"/><input type="hidden" value="<?php echo $actual; ?>" name="page"/><input type="hidden" value="<?php echo $tema; ?>" name="tema"/></td>
                            </tr>

                            <tr>
                                <td align="right" valign="middle">
                                    <div onclick="xsubmit()" id="btnBuscar" name="btnBuscar" class="myButton">Buscar</div></td>
                                </tr>

                                <tr>
                                    <td height="1" valign="middle" bgcolor="#bbbbbb"></td>
                                </tr>

                            </table></td>
                        </tr>

                    </table>

                </td></tr></table>
            </form>
        </body>
        </html>
