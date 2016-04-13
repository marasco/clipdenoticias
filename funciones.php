<?php
//setcookie("usuariosXXXXXXXXXX","fran",time()+3600*24*60,"/");
function texto($a)
{
$a = str_replace("<","&lt;",$a);
$a = str_replace(">","&gt;",$a);
$a = str_replace("'","&quot;",$a);
$a = str_replace(chr(34),"&quot;",$a);

return $a;
}
function logout($nombre_cook)
	{
		setcookie($nombre_cook,"",0,"/");
		setcookie("iadmin","",0,"/");
		header("Location: index.php?ok");
	}

function aleatoria($cant){
	for ($j=1;$j<=$cant;$j++)
		$nombre.=rand(0,9);
	return $nombre;

};
function text_replace($s)
{
$s = str_replace("'","&apos;",$s);
$s = str_replace(chr(34),"&quot;",$s);
$s = str_replace("´","&apos;",$s);
return $s;
}
function validar_str($string)
{
$string = str_replace("'",chr(34),$string);
$string = str_replace(";",",",$string);
$string = str_replace("<","(",$string);
$string = str_replace(">",")",$string);
return $string;

};


?>