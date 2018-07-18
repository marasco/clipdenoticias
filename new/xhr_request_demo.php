<?php

$em = $_REQUEST['email'];
if (empty($em)){
die('OK');
}


//mail("contact@clipdenoticias.com", "Contact Landing Pezo", "email: ".$em);


 
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('EST');

require_once 'class.phpmailer.php';
require_once 'class.smtp.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'smtp.clipdenoticias.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 465;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'ssl';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "francisco@clipdenoticias.com";

//Password to use for SMTP authentication
$mail->Password = "Franchesc0";

$mail->SMTPOptions = array(
'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
)
);
$mail->setFrom('cmarasco@clipdenoticias.com', 'Clip de Noticias');
$mail->addAddress('cmarasco@clipdenoticias.com', 'Clip de Noticias');
$mail->addAddress('francisco@flydevs.com', 'Clip de Noticias');
$mail->Subject = 'Solicitud de DEMO';
$html='';
$html.= "<p>El Email {$em} ha solicitado una demo.</p>";
//$html.= "<p>Website: {$website}</p>";
//$html.= "<p>Message: {$message}</p>";
$mail->msgHTML($html);

//Replace the plain text body with one created manually
$mail->AltBody = "Email: {$em}";

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "OK";
}
