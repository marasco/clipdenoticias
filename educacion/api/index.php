<?php
header('Content-Type: application/json; charset=UTF-8');
 if (isset($_REQUEST['user']) && isset($_REQUEST['pwd'])  ){
    if ( $_REQUEST['user'] == 'educacion' && $_REQUEST['pwd']=='ed09ghjk83929' ){
        
    }else{
        die('Not Authorized.');
    }
}else{
    die('Not Authorized.');
}
require_once 'dbconnect.php';
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
//$filtro_fecha_hoy = " and n.fecha_nota = CURRENT_DATE() ";
//$dia_h = date("D");
$ini = 0;
$page = 0;
$cpp = 50;

if (isset($_REQUEST['qtd'])){
    $cpp = intval($_REQUEST['qtd']);
}
if (isset($_REQUEST['page'])){
    $page = intval($_REQUEST['page']);
}
$ini = $page*$cpp;
$lastIdQuery = "";
if (isset($_REQUEST['lastId'])){
    $lastId = intval($_REQUEST['lastId']);
    $lastIdQuery = " n.id > $lastId AND ";
}
//echo $dia_h."<br>";
/*switch ($dia_h) {
    case "Sun": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-2 ";
    case "Sat": $filtro_dia_hoy = " and n.fecha_nota = CURRENT_DATE()-1 ";
    case "Mon": $filtro_dia_hoy = " and ( n.fecha_nota = CURRENT_DATE()-2 or n.fecha_nota = CURRENT_DATE()-1 or n.fecha_nota = CURRENT_DATE() )  ";
}
*/
$db = new db();
$db->connect();
$tema = 'EDU';
$query = "
    SELECT 
    n.id as 'id',
    DATE_FORMAT(n.fecha_nota,'%d/%m/%Y %H:%i:%s') as 'fecha',
    n.titulo as 'titulo', 
    n.volanta as 'volanta', 
    n.copete as 'copete', 
    n.texto 'texto', 
    f.nombre 'medio', 
    FORMAT(n.tamimg,0) as 'destacado', 
    n.region as 'seccion'
    FROM tnotas n, tautores f 
    WHERE 
    $lastIdQuery
    n.codigo_tema = '" . $tema . "'  
        and f.id = n.fuente 
        and n.estado = 1 " . $filtro_fecha_hoy . " order by n.fecha_nota desc, n.id desc, n.region asc LIMIT $ini, $cpp";

$r = array();
 
$r['provider'] = 'clipdenoticias';
$r['date'] = date('d/m/Y h:i:s',time());
$r['client'] = 'mod.educacion';
 
$r['config']['page'] = $page;
$r['config']['start'] = $ini;
$r['config']['quantity'] = $cpp;


$res = $db->query($query);
while ($p = mysqli_fetch_assoc($res)) {
    foreach($p as $k=>$n){
        $p[$k]=utf8_encode($n);
    }
    $p['url'] = 'http://www.clipdenoticias.com/educacion/vernota.php?id='.$p['id'];
    $r['notas'][] = $p;
   // die(print_r($p));
}
//die($query);
echo json_encode($r);
?>
