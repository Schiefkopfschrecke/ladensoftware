<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'classes/PHPMailer-master/src/PHPMailer.php';
require 'classes/PHPMailer-master/src/SMTP.php';
require 'classes/PHPMailer-master/src/Exception.php';
error_reporting(E_ALL);

// include functions, configurations and header
include 'inc/functions.php';

include 'inc/config.php';
include 'config/Secrets.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Unbenanntes Dokument</title>
</head>

<body>
	hallo3456
	<?
	$an = 'a.koenig@immerda.ch';
	$von = 'info@gemeinsaftladen';
	$betreff = 'Testmail';
	$mail_inhalt = 'hallo<br>du';
	$mail = new PHPMailer();
	mail_senden_smtp($an, $von, $betreff, $mail_inhalt);

	/*			
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = Secrets::MailHost;
	$mail->SMTPAuth = Secrets::MailSMTPAuth;
	$mail->Username = Secrets::MailUser;
	$mail->Password = Secrets::MailPassword;
	$mail->SMTPSecure = Secrets::MailSMTPSecure;
	$mail->Port = Secrets::MailPort;

	$mail->setFrom(Secrets::MailFrom, Secrets::MailFromName);
	$mail->addReplyTo(Secrets::MailFrom, 'Andreas');
	$mail->addAddress('andreas.koenig@silviva.ch');
	$mail->Subject = $betreff;
	$mail->isHTML(true);
	$mail->Body = $mail_inhalt;
	if(!$mail->send()){
    	echo 'Message could not be sent.';
    	echo 'Mailer Error: ' . $mail->ErrorInfo;
	}else{
    	echo 'Message has been sent';
	}
*/

	?>
</body>

</html>