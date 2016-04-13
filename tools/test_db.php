<?php

  	$db = mysql_connect("192.168.0.192","mysql_root","fran21");
    mysql_select_db("clipping",$db);
	#$query = "SELECT DISTINCT(codigo_tema) FROM tnotas;";#
	$query = "SELECT * FROM tnotas WHERE id = 160414;";
	$con = mysql_query($query,$db);
	$cant_not = 0;
	while ($rs = mysql_fetch_array($con))
	{
		echo "<pre>";
		print_r($rs);
		echo "</pre>";
	}
	die;