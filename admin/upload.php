<?php
include "../funciones.php";


//	echo "Ult carga arranca en = ".$ult_carga_ini." y termina en ".$ult_carga_fin;
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
$SCREEN = "";

$admin = "";
if (isset($_COOKIE['iadmin'])) {
    if (strlen($_COOKIE['iadmin']) > 0)
        $admin = $_COOKIE['iadmin'];
}
if (strlen($admin) == 0) {
    header("location: ../index.php?msg=noadmin");
}

if ($_POST['Guardar_autor'] == "Guardar") {
    if (($_POST['n_autor'] != "")) {
        //echo $_POST['n_autor'];
        $db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
        mysql_select_db("clipping", $db);
        $var = texto($_POST['n_autor']);
        //	echo $var."ha";
        $query = "insert into tautores (nombre) values ('" . $_POST['n_autor'] . "')";
        mysql_query($query, $db);
        //echo $query;
        mysql_close($db);
        $SCREEN = "Se ha guardado el medio.";
    } else {
        $SCREEN = "Debes escribir el nombre del autor.";
    }
}
if ($_POST['Guardar_susc'] == "Guardar") {
    if (($_POST['s_temas'] != "") && ($_POST['s_password'] != "") && ($_POST['s_email'] != "") && ($_POST['s_usuario'] != "") && ($_POST['s_tipo'])) {
        $db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
        mysql_select_db("clipping", $db);
        $query = "insert into tsuscripciones (fecha_creacion,codigo_tema,usuario,contrasena,estilo,email,tipo,estado) values (NOW(),'" . $_POST['s_temas'] . "','" . $_POST['s_usuario'] . "','" . $_POST['s_password'] . "','" . $_POST['s_estilo'] . "','" . $_POST['s_email'] . "','" . $_POST['s_tipo'] . "',1)";
        //echo $query;
        mysql_query($query, $db);
        mysql_close($db);
        $SCREEN = "Se ha guardado la suscripcion.";
    } else {
        $SCREEN = "Faltan datos para guardar la suscripcion.";
    }
}

if ($_POST['Guardar_tema'] == "Guardar") {
    if (($_POST['n_tema'] != "") && ($_POST['d_tema'] != "") && ($_POST['cod_tema'] != "")) {
        $db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
        mysql_select_db("clipping", $db);
        $query = "insert into ttemas (nombre,codigo,descripcion) values ('" . $_POST['n_tema'] . "','" . $_POST['cod_tema'] . "','" . $_POST['d_tema'] . "')";
        mysql_query($query, $db);
        mysql_close($db);
        $SCREEN = "Se ha guardado el tema.";
    } else {
        $SCREEN = "Faltan datos para guardar el tema.";
    }
} else {
    //$SCREEN = "No se clic en Guardar";
};




if ($_POST['btnAceptar'] == "Aceptar") {
    $campo_1 = texto($_POST['campo_1']);
    $campo_2 = $_POST['campo_2'];
    $campo_3 = texto($_POST['campo_3']);
    $campo_4 = $_POST['campo_4'];
    $campo_44 = $campo_5 = text_replace($_POST['campo_5']);
    $region = $_POST['region'];
    $campo_6 = texto($_POST['campo_6']);
    $campo_7 = intval($_POST['fanio']) . "/" . intval($_POST['fmes']) . "/" . intval($_POST['fdia']);
    $campo_8 = texto($_POST['campo_8']);
    $campo_extra = texto($_POST['campo_extra']);
    $campo_extra2 = texto($_POST['campo_extra2']);

    if ($_POST['dest'])
        $campoD = 1;
    else
        $campoD = 0;
    //echo $campo_7;
    if (($campo_1 == "") || ($campo_3 == "") || ($campo_4 == "") || ($campo_5 == "")) {
        $SCREEN = "Faltan datos necesarios para la carga.";
    }

    if ($SCREEN == "") {
        //echo $campo_6;
        $db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
        mysql_select_db("clipping", $db);
        $query55 = $query = "insert into tnotas
        (codigo_tema,fecha_creacion,fecha_nota,titulo,volanta,copete,texto,fuente,estado,tamimg,destacado,region,link,extra,extra2)
        values ('" . $campo_3 . "',NOW(),'" . $campo_7 . "','" . $campo_1 . "','" . $campo_2 . "','" . $campo_4 . "','" . $campo_5 . "','" . $campo_6 . "',1," . intval($_POST['tamimg']) . "," . $campoD . ",'" . $region . "', '".$campo_8."', '".$campo_extra."', '".$campo_extra2."')";
        //echo $query;
        $con = mysql_query($query, $db);
        //echo $con;
        $query = "select max(id) from tnotas where estado = 1";
        $con = mysql_query($query, $db);
        if (mysql_affected_rows() == 1) {
            while ($rs = mysql_fetch_array($con)) {
                $id_imagen = $rs[0];
                SubirImagen($id_imagen);
            }
            $SCREEN = "Se han guardado los datos.";
        } else {
            $SCREEN = "ATENCION: No se han guardado los datos.";
            //echo "No notas";
        }






        $campo_1 = "";
        $campo_2 = "";
        $campo_3 = "";
        $campo_4 = "";
        $campo_5 = "";
        $campo_6 = "";


        //header("location: micuenta/opciones.php");		
    }
}
$lista_temas = cargar_temas();
$lista_autores = cargar_autores();
$lista_estilos = cargar_estilos();
$tcategorias = cargar_categorias();

function cargar_estilos() {
    
}

;

function cargar_temas() {
    $db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
    mysql_select_db("clipping", $db);
    $con = mysql_query("select codigo,nombre from ttemas order by nombre ASC", $db);
    $lista_temas = "";
    while ($results = mysql_fetch_array($con)) {
        $lista_temas = $lista_temas . "<option value='" . $results[0] . "'>" . $results[1] . "</option>";
    };
    return $lista_temas;
}

;

function cargar_categorias() {
    $db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
    mysql_select_db("clipping", $db);
    $con = mysql_query("select id,nombre from tcategorias order by id asc", $db);
    $tcategorias = "<option value=0>&nsbp;</option>";
    while ($results = mysql_fetch_array($con)) {
        $tcategorias.= "<option value='" . $results[0] . "'>" . $results[1] . "</option>";
    };
    return $tcategorias;
}

;

function cargar_autores() {
    $db = mysql_connect("192.168.0.192", "mysql_root", "fran21");
    mysql_select_db("clipping", $db);
    $con = mysql_query("select id,nombre from tautores order by nombre ASC", $db);
    $lista_autores = "";
    while ($results = mysql_fetch_array($con)) {
        //	echo $results[1];
        $lista_autores = $lista_autores . "<option value='" . $results[0] . "'>" . $results[1] . "</option>";
    };
    return $lista_autores;
}

;

function SubirImagen($id) {
    if ($_FILES['imagen']['name'] != "") {
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $x = 1;
            $archivo_destino = $_SERVER['DOCUMENT_ROOT'] . "/imgnotas/" . $id . "_" . $x . ".jpg";
            move_uploaded_file($_FILES['imagen']['tmp_name'], $archivo_destino);
            echo "Subiendo imagen: $archivo_destino";
        }
    }

    for ($x = 2; $x < 7; $x++) {
        if ($_FILES['imagen' . $x]['name'] != "") {
            if (is_uploaded_file($_FILES['imagen' . $x]['tmp_name'])) {
                $archivo_destino = $_SERVER['DOCUMENT_ROOT'] . "/imgnotas/" . $id . "_" . $x . ".jpg";
                move_uploaded_file($_FILES['imagen' . $x]['tmp_name'], $archivo_destino);
            }
        }
    }
}

function achicar($tamano, $img_s, $img_save) {

    $image = $img_s;
    $iOriginal = imagecreatefromjpeg($image);
    $porcentaje = 99;
    $ancho = $iAncho = imagesx($iOriginal);
    $alto = $iAlto = imagesy($iOriginal);
    while (( $ancho > $tamano)) {
        $ancho = ($porcentaje * $ancho) / 100;
        $alto = ($porcentaje * $alto) / 100;
    }
    settype($ancho, "integer");
    settype($alto, "integer");
    $iNueva = imagecreate($ancho, $alto);
    imagejpeg($iNueva, "e.jpg");
    $iNueva = imagecreatefromjpeg("e.jpg");
    imagecopyresampled($iNueva, $iOriginal, 0, 0, 0, 0, $ancho, $alto, $iAncho, $iAlto);
    $img2 = $img_save;
    imagejpeg($iNueva, $img2);
    imagedestroy($iNueva);
    return $img2;
}
?>


<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>| clipdenoticias.com | - El resumen de noticias que usted necesita</title>
    <link rel="stylesheet" href="../style.css" type="text/css">
    <script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
    <script language="javascript" src="../funciones.js" type="text/javascript"></script>
    <script language="JavaScript">
        function mostrar_cat(obj)
        {
                //alert(obj.value + " / " + document.getElementById('tr_cat').style.display);
                if (obj.value == 'Oportunidades de Negocios')
                    document.getElementById('tr_cat').style.display = "block";
                else
                    document.getElementById('tr_cat').style.display = "none";


            }

            function codigo(txtArea) {
                txtRange = document.getElementById(txtArea).createTextRange();
                txtContainer = txtRange.parentElement().name;
                objRange = document.selection.createRange();
                hiliteTxt = objRange.text;
                toBoldTxt = "<b>" + hiliteTxt + "</b>";
                if (hiliteTxt != "") {
                    objRange.text = toBoldTxt;
                }
            }
        </script>
    </head>

    <body bgcolor="#ffffff">
        <form name="login" method="POST" id="login" action="upload.php" enctype="multipart/form-data">
            <table width="700" border="0" class="TABLA_GRIS" align="center" cellpadding="10" cellspacing="0">
                <tr>
                    <td>
                        <table width="680" border="0" align="center" cellpadding="2" cellspacing="0">
                            <tr>
                                <td  colspan="2" align="center" valign="top"><script type="text/javascript">
                                    AC_FL_RunContent('codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0', 'width', '680', 'height', '180', 'src', '../logo2', 'quality', 'high', 'pluginspage', 'http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash', 'movie', '../logo2'); //end AC code
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
                                    <td width="120" align="center" ><a href="../index.php"  onMouseOver="javascript:document.getElementById('hom').style.background = '#f5f5f5';" onMouseOut="javascript:document.getElementById('hom').style.background = '#ffffff';" target="_self" class="LINKS_MENU">
                                        <div id="hom">Home</div>
                                    </a></td>

                                    <td width="120" align="center" ><a class="LINKS_MENU" href="mysql.php">Visualizar Datos</a></td>
                                    <td align="center" ><a class="LINKS_MENU" href="upload.php">Cargar </a></td>
                                    <td width="120" align="center" ><a href="../index.php?logout=yes" target="_self" class="LINKS_MENU">Salir</a></td> </tr>

                                </table>
                            </td></tr>
                            <tr><td colspan="2"><hr align="center" size="1" noshade="noshade" color="#CCCCCC"></td></tr>



                            <tr>
                                <td align="left" valign="top"><table width="680" border="0" cellspacing="0" cellpadding="2">
                                    <?php if ($SCREEN != "") echo "<tr><td colspan='2' align='center' bgcolor='#FEEBC7' class='lnk_pagina_actual style1'>&nbsp;* " . $SCREEN . "</td></tr>"; ?>
                                    <tr>
                                        <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("suscripcion");>: Agregar Suscripci?n :</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td height="5"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div id="suscripcion" style="height:1px; visibility:hidden; overflow:auto;">
                                                <table width="660" border="0" cellspacing="0" cellpadding="1">
                                                    <tr>
                                                        <td width="200" height="20" align="right" valign="top" class="texto_verde">Usuario :</td>
                                                        <td width="10" height="20">&nbsp;</td>
                                                        <td align="left" valign="top" class="LINKS_MENU">
                                                            <input type="text" name="s_usuario" id="s_usuario"  maxlength="50" class="form_textbox_200"/>
                                                        </td></tr>
                                                        <tr>
                                                            <td width="200" height="20" align="right" valign="top" class="texto_verde">Contrase?a :</td>
                                                            <td width="10" height="20">&nbsp;</td>
                                                            <td align="left" valign="top" class="LINKS_MENU">
                                                                <input type="password" name="s_password" id="s_password"  maxlength="50" class="form_textbox_200"/>
                                                            </td></tr>
                                                            <tr>
                                                                <td width="200" height="20" align="right" valign="top" class="texto_verde">Email :</td>
                                                                <td width="10" height="20">&nbsp;</td>
                                                                <td align="left" valign="top" class="LINKS_MENU">
                                                                    <input type="text" name="s_email" id="s_email"  maxlength="50" class="form_textbox_200"/>
                                                                </td></tr>
                                                                <tr>
                                                                    <td width="200" height="20" align="right" valign="top" class="texto_verde">Tema :</td>
                                                                    <td width="10" height="20">&nbsp;</td>
                                                                    <td align="left" valign="top" class="LINKS_MENU">
                                                                        <select name="s_temas" id="s_temas" class="form_textbox_200">
                                                                            <?php echo $lista_temas; ?>
                                                                        </select>
                                                                    </td></tr>   <tr>
                                                                    <td width="200" height="20" align="right" valign="top" class="texto_verde">Estilo :</td>
                                                                    <td width="10" height="20">&nbsp;</td>
                                                                    <td align="left" valign="top" class="LINKS_MENU">
                                                                        <select name="s_estilo" id="s_estilo"  class="form_textbox_200">
                                                                            <?php echo $lista_estilos; ?>
                                                                        </select>


                                                                    </td></tr>
                                                                    <tr>
                                                                        <td width="200" height="20" align="right" valign="top" class="texto_verde">Periodicidad :</td>
                                                                        <td width="10" height="20">&nbsp;</td>
                                                                        <td align="left" valign="top" class="LINKS_MENU">
                                                                            <select name="s_tipo" id="s_tipo"  class="form_textbox_200">
                                                                                <option>Diario</option>
                                                                                <option>Semanal</option>
                                                                                <option>Demo</option>
                                                                            </select>


                                                                        </td></tr>

                                                                        <tr>
                                                                            <td width="200" height="20" align="right" valign="top" class="texto_verde"></td>
                                                                            <td width="10" height="20">&nbsp;</td>
                                                                            <td align="left" valign="top" class="LINKS_MENU">
                                                                                <input type="submit" value="Guardar"  class="BOTON_NARANJA"  id="Guardar_susc" name="Guardar_susc"/>
                                                                            </td></tr>
                                                                        </table></div></td></tr>
                                                                        <tr>
                                                                            <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("temas");>: Agregar Tema :</td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td height="5"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div id="temas" style="height:1px; visibility:hidden; overflow:auto;">
                                                                                    <table width="660" border="0" cellspacing="0" cellpadding="1">
                                                                                        <tr>
                                                                                            <td width="200" height="20" align="right" valign="top" class="texto_verde">Nombre de tema :</td>
                                                                                            <td width="10" height="20">&nbsp;</td>
                                                                                            <td align="left" valign="top" class="LINKS_MENU">
                                                                                                <input type="text" name="n_tema" id="n_tema"  class="form_textbox_200"/>
                                                                                            </td></tr>
                                                                                            <tr>
                                                                                                <td width="200" height="20" align="right" valign="top" class="texto_verde">C?digo de tema :</td>
                                                                                                <td width="10" height="20">&nbsp;</td>
                                                                                                <td align="left" valign="top" class="LINKS_MENU">
                                                                                                    <input type="text" name="cod_tema" id="cod_tema"  class="form_textbox_200"/>
                                                                                                </td></tr>   <tr>
                                                                                                <td width="200" height="20" align="right" valign="top" class="texto_verde">Descripci?n de tema :</td>
                                                                                                <td width="10" height="20">&nbsp;</td>
                                                                                                <td align="left" valign="top" class="LINKS_MENU">
                                                                                                    <input type="text" name="d_tema" id="d_tema"  class="form_textbox_200"/>
                                                                                                </td></tr> <tr>
                                                                                                <td width="200" height="20" align="right" valign="top" class="texto_verde"></td>
                                                                                                <td width="10" height="20">&nbsp;</td>
                                                                                                <td align="left" valign="top" class="LINKS_MENU">
                                                                                                    <input type="submit" value="Guardar"  class="BOTON_NARANJA"  id="Guardar_tema" name="Guardar_tema"/>
                                                                                                </td></tr>
                                                                                            </table></div></td></tr>

                                                                                            <tr>
                                                                                                <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("autores");>: Agregar Medio :</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td height="5"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td colspan="2">	<div id="autores" style="height:1px; visibility:hidden; overflow:auto;"><table width="660" border="0" cellspacing="0" cellpadding="1">
                                                                                                    <tr>
                                                                                                        <td width="200" height="20" align="right" valign="top" class="texto_verde">Nombre de medio :</td>
                                                                                                        <td width="10" height="20">&nbsp;</td>
                                                                                                        <td align="left" valign="top" class="LINKS_MENU">
                                                                                                            <input type="text" name="n_autor" id="n_autor"  class="form_textbox_200"/>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="200" height="20" align="right" valign="top" class="texto_verde"></td>
                                                                                                        <td width="10" height="20">&nbsp;</td>
                                                                                                        <td align="left" valign="top" class="LINKS_MENU">
                                                                                                            <input type="submit" value="Guardar"  class="BOTON_NARANJA"  id="Guardar_autor" name="Guardar_autor"/>
                                                                                                        </td></tr>
                                                                                                    </table></div></td></tr>

                                                                                                    <tr>
                                                                                                        <td width="198" align="center" bgcolor="#FF6600" class="TITULO_BLANCO" style="cursor:pointer;" onClick=showObj("notas");>: Agregar Nota :</td><td width="474"></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td height="5"></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td colspan="2">	<div id="notas" style="height:1000px; visibility:visible; overflow:auto;">
                                                                                                            <table width="660" border="0" cellspacing="0" cellpadding="1">
                                                                                                                <tr>
                                                                                                                    <td width="200" height="20" align="right" valign="top" class="texto_verde">Tema :</td>
                                                                                                                    <td width="10" height="20">&nbsp;</td>
                                                                                                                    <td align="left" valign="top" class="LINKS_MENU">
                                                                                                                        <select name="campo_3" id="campo_3"  onchange=mostrar_cat(this);  class="form_textbox_200">
                                                                                                                            <?php echo $lista_temas; ?>
                                                                                                                        </select>
                                                                                                                        * </td></tr>
                                                                                                                        <tr>
                                                                                                                            <td width="200" height="30" align="right" valign="top" class="texto_verde">Regi&oacute;n:</td>
                                                                                                                            <td width="10" height="30">&nbsp;</td>
                                                                                                                            <td height="30" class="LINKS_MENU">
                                                                                                                                <select name='region'>
                                                                                                                                    <option value=''></option>
                                                                                                                                    <option value='Argentina'>Argentina</option>
                                                                                                                                    <option value='Bolivia'>Bolivia</option>
                                                                                                                                    <!-- <option value='Brasil'>Brasil</option>-->
                                                                                                                                    <option value='Chile'>Chile</option>
                                                                                                                                    <!-- <option value='Colombia'>Colombia</option>-->
                                                                                                                                    <!-- <option value='Peru'>Per&uacute;</option>-->
                                                                                                                                    <option value='Uruguay'>Uruguay</option>
                                                                                                                                    <option value='Paraguay'>Paraguay</option>
                                                                    <!-- <option value='Latinoamerica'>Latinoamerica</option>
                                                                     <option value='Asia'>Asia</option>
                                                                     <option value='Centroamerica'>Centroamerica</option>
                                                                     <option value='Norteamerica'>Norteamerica</option>
                                                                     <option value='Sudamerica'>Sudamerica</option>
                                                                     <option value='Europa'>Europa</option>
                                                                 --> <option value='Educacion'>Educacion</option>
                                                                 <option value='Debate'>Debate</option>
                                                                 <option value='Saint Maarten'>Saint Maarten</option>
                                                                 <option value='New York'>New York</option>
                                                                 <option value='IPW'>IPW</option>
                                                                 <option value='Simon Shopping Destinations'>Simon Shopping Destinations</option>
                                                                 <option value='Las Vegas'>Las Vegas</option>
                                                                 <option value='Dubai'>Dubai</option>
                                                                 <option value='Anguilla'>Anguilla</option>
                                                                 
                                                             </select> </td>     
                                                         </tr>

                                                         <tr>
                                                            <td width="200" height="30" align="right" valign="top" class="texto_verde">Volanta :</td>
                                                            <td width="10" height="30">&nbsp;</td>
                                                            <td height="30" class="LINKS_MENU"><textarea  name="campo_2"  class="form_textarea2" id="campo_2"></textarea> </td>     
                                                        </tr>
                                                        <tr>
                                                            <td width="200" height="30" align="right" valign="top" class="texto_verde">Titulo de la nota:<br><input type='checkbox' name='dest' id='dest' /> Destacado</td>
                                                            <td width="10" height="30">&nbsp;</td>
                                                            <td height="30" class="LINKS_MENU"><textarea  name="campo_1"  class="form_textarea2" id="campo_1"></textarea>
                                                                * </td>     
                                                            </tr>


                                                            <tr>
                                                                <td width="200" height="30" align="right" valign="top" class="texto_verde">Copete :</td>
                                                                <td width="10" height="30">&nbsp;</td>
                                                                <td height="30" class="LINKS_MENU"><textarea  name="campo_4"  class="form_textarea4" id="campo_4" ></textarea>  
                                                                    * </td>     
                                                                </tr>
                                                                <tr>
                                                                    <td width="200" height="30" align="right" valign="top" class="texto_verde">Texto :</td>
                                                                    <td width="10" height="30">&nbsp;</td>
                                                                    <td height="30" class="LINKS_MENU"> <textarea  name="campo_5"  class="form_textarea8" id="campo_5" ></textarea>  
                                                                        * </td>     
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="200" height="30" align="right" valign="top" class="texto_verde">Link :</td>
                                                                        <td width="10" height="30">&nbsp;</td>
                                                                        <td height="30" class="LINKS_MENU"><br /><input  name="campo_8"   id="campo_8" type='text' />  
                                                                        </td>     
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="200" height="30" align="right" valign="top" class="texto_verde">Medio  :</td>
                                                                        <td width="10" height="30">&nbsp;</td>
                                                                        <td height="30" valign="top" class="LINKS_MENU"><select  name="campo_6"  class="form_textbox_200" id="campo_6" >
                                                                            <?php
                                                                            echo $lista_autores;
                                                                            ?> </select>
                                                                            * </td> 
                                                                        </tr>  <tr>
                                                                        <td width="200" height="30" align="right" valign="top" class="texto_verde">Fecha de la nota  :</td>
                                                                        <td width="10" height="30">&nbsp;</td>
                                                                        <td height="30" align="left" valign="top" class="LINKS_MENU"><select name="fdia" id="fdia"  class="form_2numeros">


                                                                            <?php
                                                                            for ($a = 1; $a <= 31; $a++)
                                                                                if ($a == date("d"))
                                                                                    echo "<option selected='selected'>" . $a . "</option>";
                                                                                else
                                                                                    echo "<option>" . $a . "</option>";
                                                                                ?>
                                                                            </select>
                                                                            &nbsp;<select name="fmes" id="fmes"  class="form_textbox_60">
                                                                            <?php
                                                                            
                                                                            for ($a = 1; $a <= 12; $a++)
                                                                                if ($a == date("m"))
                                                                                    echo "<option value=" . $a . " selected='selected'>" . $lista_meses[$a - 1] . "</option>";
                                                                                else
                                                                                    echo "<option  value=" . $a . ">" . $lista_meses[$a - 1] . "</option>";
                                                                                ?>

                                                                            </select>
                                                                            <span class="LINKS_MENU">
                                                                                <select name="fanio" id="fanio"  class="form_4numeros">
                                                                                    <?php 
                                                                                    $date = date('Y');
                                                                                    for($i=0;$i<5;$i++):
                                                                                    ?>

                                                                                	<option <?php if (!$i){ echo 'selected=selected'; }?>>
                                                                                	<?=(((int)$date)-$i)?></option> 
                                                                                	<?php
                                                                                	endfor;
                                                                                	?>
                                                                                </select>
                                                                            </span>  * </td>    
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="200" height="30" align="right" valign="top" class="texto_verde">Value Media :</td>
                                                                            <td width="10" height="30">&nbsp;</td>
                                                                            <td height="30" class="LINKS_MENU"><br /><input  name="campo_extra"   id="campo_extra" type='text' />  
                                                                            </td>     
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="200" height="30" align="right" valign="top" class="texto_verde">Pageviews :</td>
                                                                            <td width="10" height="30">&nbsp;</td>
                                                                            <td height="30" class="LINKS_MENU"><br /><input  name="campo_extra2"   id="campo_extra2" type='text' />  
                                                                            </td>     
                                                                        </tr>
                                                                        <tr class="texto_verde">
                                                                            <td width="200" height="30" align="right" valign="top" class="texto_verde">Im?gen :
                                                                                <td></td><td align="left" valign="top">

                                                                                <input type="file" class="datos_textbox" id="imagen" name="imagen" /></td>
                                                                            </tr>
                                                                            <tr class="texto_verde" id='tr_cat' style='display:none'>
                                                                                <td width="200" height="30" align="right" valign="top" class="texto_verde">Categoria :
                                                                                    <td></td><td align="left" valign="top">

                                                                                    <select name="tamimg" id="tamimg" class="texto_verde">

                                                                                        <?php
                                                                                        echo $tcategorias;
                                                                                        ?>

                                                                                    </select></td>
                                                                                </tr>

                                                                                <tr class="texto_verde">
                                                                                    <td height="20" colspan="3" align="center" valign="middle" bgcolor="#eeeeee" class="texto_verde">Las siguientes im?genes se muestran en 448 pixeles de ancho	  </td>
                                                                                </tr>
                                                                                <tr class="texto_verde">
                                                                                    <td width="200" height="30" align="right" valign="top" class="texto_verde">Im?gen 2 :
                                                                                        <td></td><td align="left" valign="top">

                                                                                        <input type="file" class="datos_textbox" id="imagen2" name="imagen2" /></td>
                                                                                    </tr>
                                                                                    <tr class="texto_verde">
                                                                                        <td width="200" height="30" align="right" valign="top" class="texto_verde">Im?gen 3 :
                                                                                            <td></td><td align="left" valign="top">

                                                                                            <input type="file" class="datos_textbox" id="imagen3" name="imagen3" /></td>
                                                                                        </tr>
                                                                                        <tr class="texto_verde">
                                                                                            <td width="200" height="30" align="right" valign="top" class="texto_verde">Im?gen 4 :
                                                                                                <td></td><td align="left" valign="top">

                                                                                                <input type="file" class="datos_textbox" id="imagen4" name="imagen4" /></td>
                                                                                            </tr>
                                                                                            <tr class="texto_verde">
                                                                                                <td width="200" height="30" align="right" valign="top" class="texto_verde">Im?gen 5 :
                                                                                                    <td></td><td align="left" valign="top">

                                                                                                    <input type="file" class="datos_textbox" id="imagen5" name="imagen5" /></td>
                                                                                                </tr>
                                                                                                <tr class="texto_verde">
                                                                                                    <td width="200" height="30" align="right" valign="top" class="texto_verde">Im?gen 6 :
                                                                                                        <td></td><td align="left" valign="top">

                                                                                                        <input type="file" class="datos_textbox" id="imagen6" name="imagen6" /></td>
                                                                                                    </tr>


                                                                                                    <tr valign="middle"><td height="30"></td>
                                                                                                        <td height="30"></td>
                                                                                                        <td height="30" align="left"><input name="btnAceptar" type="submit" class="BOTON_NARANJA" id="btnAceptar" value="Aceptar"></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>

                                                                        </table></td>
                                                                    </tr>

                                                                </table>
                                                            </form>

                                                        </body>
                                                        </html>
