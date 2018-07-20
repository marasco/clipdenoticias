<?php 

$MOSTRAR='';
if (!empty($_POST)){
	if ($_POST["btn1"] == "Ingresar") {

	    if (!empty($_POST["txt1"]) && !empty($_POST["txt2"])) {

	        $us = $_POST["txt1"];
	        $co = $_POST["txt2"];
	        $db = mysql_connect("190.228.29.67", "mysql_root", "fran21");
	        mysql_select_db("clipping", $db);
	        $query = "select S.usuario from tsuscripciones S where S.usuario = '" . $us . "' and S.contrasena = '" . $co . "'";
	        mysql_query($query, $db);
	        $login_ok = 0;

	        if (mysql_affected_rows($db) > 0){
	            $login_ok = 1;
	        }

	        if ($login_ok == 1) {

	            setcookie("usuarios", $us, time() + 3600 * 24 * 60, "/");
	            header("location: ../principal.php");
	        } else {
	            if (($us == "candela") && ($co == "tuyosiempre")) {

	                setcookie("iadmin", "candela", time() + 3600 * 24 * 60, "/");
	                header("location: ../admin/upload.php");
	                //$MOSTRAR = $us. "-". $co;
	            } else {

	                $MOSTRAR = "Nombre de usuario y contrase&ntilde;a incorrectos.";
	            }
	        }
	    } else {
	        $MOSTRAR = "Debes escribir tu nombre de usuario y contrase&ntilde;a.";
	    }
	}
	}
?>
<!-- FlatFy Theme - Andrea Galanti /-->
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Flatfy Free Flat and Responsive HTML5 Template ">
    <meta name="author" content="">

    <title>Clip de Noticias</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
 
    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Arvo:400,700' rel='stylesheet' type='text/css'>
	
    <!-- Custom CSS-->
    <link href="css/general.css" rel="stylesheet">
	
	 <!-- Owl-Carousel -->
    <link href="css/custom.css" rel="stylesheet">
	<link href="css/owl.carousel.css" rel="stylesheet">
    <link href="css/owl.theme.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/jquery.growl.css" rel="stylesheet">

	<!-- Magnific Popup core CSS file -->
	<link rel="stylesheet" href="css/magnific-popup.css"> 
	
	<script src="js/modernizr-2.8.3.min.js"></script>  <!-- Modernizr /-->
	<!--[if IE 9]>
		<script src="js/PIE_IE9.js"></script>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="js/PIE_IE678.js"></script>
	<![endif]-->

	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
	<![endif]-->

</head>

<body id="home">

	<!-- Preloader -->
	<div id="preloader">
		<div id="status"></div>
	</div>
	
	<!-- NavBar-->
	<nav class="navbar-default" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#home" style="margin-top:10px;padding:0px;"><img src="../icon_transparency.png" height="50" /></a>
			</div>

			<div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					
					<li class="menuItem"><a href="./">Inicio</a></li> 
				</ul>
			</div>
		   
		</div>
	</nav> 
	
	<!-- FullScreen -->
    <div class="intro-header">
		<div class="col-xs-12 text-center abcen1">
			

	<form action="" method="POST">
			<table align="center" border="0" cellpadding="10" cellspacing="10"  style="width:auto;min-width:300px;max-width: 100%">
			    <tr bgcolor="">
			        <td bgcolor="#333" class="TITULO_BLANCO" colspan="2">
			            ACCESO
			        </td>
			    </tr>
			    <tr>
			        <td colspan="2" height="10">
			        </td>
			    </tr>
			    <tr>
			        <td align="right" class="texto_verde" valign="middle" width="90" >
			            USUARIO
			        </td>
			        <td width="90">
			            <input class="datos_textbox" id="txt1" style="color:#444" maxlength="50" name="txt1" type="text"/>
			        </td>
			    </tr>
			    <tr>
			        <td align="right" class="texto_verde" valign="middle" width="90">
			            CLAVE 
			        </td>
			        <td width="90">
			            <input class="datos_textbox" id="txt2" style="color:#444" maxlength="50" name="txt2" type="password"/>
			        </td>
			    </tr>
			    <tr>
			        <td>
			        </td>
			        <td align="left" height="30" valign="top" width="90">
			            <input class="BOTON_NARANJA" id="btn1" name="btn1" style="background:#99293d;border:none;margin:20px 10px;padding:10px" type="submit" value="Ingresar"/>
			            <br />
			            <span style="color:#fff;"><?=$MOSTRAR?></span>
			        </td>
			    </tr>
			</table>
</form>

		</div>    
        <!-- /.container -->
		<div class="col-xs-12 text-center abcen wow fadeIn">
			<div class="button_down "> 
				<a class="imgcircle wow bounceInUp" data-wow-duration="1.5s"  href="#whatis"> <img class="img_scroll" src="img/icon/circle.png" alt=""> </a>
			</div>
		</div>
    </div>
	 
	
	
	
    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
	<script src="js/owl.carousel.js"></script>
	<script src="js/jquery.growl.js"></script>
	<script src="js/script.js"></script>
	<!-- StikyMenu -->
	<script src="js/stickUp.min.js"></script>
	<script type="text/javascript">
	  jQuery(function($) {
		$(document).ready( function() {
		  $('.navbar-default').stickUp();


		});
	  });
	
	</script>
	<!-- Smoothscroll -->
	<script type="text/javascript" src="js/jquery.corner.js"></script> 
	<script src="js/wow.min.js"></script>
	<script>
	 new WOW().init();
	</script>
	<script src="js/classie.js"></script>
	<script src="js/uiMorphingButton_inflow.js"></script>
	<!-- Magnific Popup core JS file -->
	<script src="js/jquery.magnific-popup.js"></script> 
</body>

</html>
