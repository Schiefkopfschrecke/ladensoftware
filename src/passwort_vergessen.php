<?php

error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;

require 'classes/PHPMailer-master/src/PHPMailer.php';
require 'classes/PHPMailer-master/src/SMTP.php';
require 'classes/PHPMailer-master/src/Exception.php';

require_once 'config/Secrets.php';

// include functions, configurations and header
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/header.php';

// Start treating form
// check if form is posted

if ($_POST) {
	// Altes Passwort von DB aufrufen
	$sql = 'SELECT id, benutzername FROM mitglieder WHERE email = "' . $_POST['email'] . '"';
	$res = db_anfragen($sql);
	$mitglieddaten = mysqli_fetch_assoc($res);
	if (isset($mitglieddaten['id'])) {
		//Mögliche Zeichen für den String
		$zeichen = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!.:=';
		//String wird generiert
		$pw = '1';
		$anz = strlen($zeichen);
		for ($i = 0; $i < 12; $i++) {
			$pw .= $zeichen[rand(0, $anz - 1)];
		}

			$mail_inhalt = '<p>Hoi</p><p>Dein Benutzername lautet: ' . $mitglieddaten['benutzername'] . '<br>Dein neues Passwort lautet: <br>' . $pw . '<br><br>Du kannst es in deinem Benutzerkonto ändern.</p><p>Viele Grüsse<br>das GemeinSaftladen-Team</p>';
		// XX PHP-Mailer noch integrieren in Website
		//mail('a.koenig@immerda.ch', 'Passwort', '$mail_inhalt');
		// Mail verschicken
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->Host = Secrets::MailHost;
		$mail->SMTPAuth = Secrets::MailSMTPAuth;
		$mail->Username = Secrets::MailUser;
		$mail->Password = Secrets::MailPassword;
		$mail->SMTPSecure = Secrets::MailSmtpSecure;
		$mail->Port = Secrets::MailPort;
	
		$mail->setFrom(Secrets::MailFrom, Secrets::MailFromName);
//$mail->addReplyTo('info@gemeinsaftladen.ch', 'Andreas');
		$mail->addAddress($_POST['email']);
		$mail->Subject = 'Neues Passwort GemeinSaftladen';
		$mail->isHTML(true);
		$mail->CharSet = 'utf-8';
		$mail->Body = $mail_inhalt;
		if (!$mail->send()) {
			echo 'E-Mail konnte nicht gesendet werden.';
			echo 'Fehlermeldung des Mailers: ' . $mail->ErrorInfo;
		} else {
			echo 'E-Mail wurde versandt.';
		}

		// altes Passwort aus Eingabeformular verschlüsseln
		$pw = sha1($pw);
		$pw = sha1($pw . 'transformator');

		// Beide alte Passwörter vergleichen, wenn korrekt

		// Neues Passwort wird in DB gespeichert
		$sql = 'UPDATE 
						mitglieder 
					SET
						passwort = "' . $pw . '" WHERE id = ' . $mitglieddaten['id'];
		$res = db_anfragen($sql);

		// if entry in DB is done create feedback on website 
		if ($res) {
			// create text for website
			echo '<div style="border:2px solid #e52300; padding:5px; "><b>Wir haben dir ein neues Passwort auf ' . $_POST['email'] . ' gesendet. Ändere es im Benutzerkonto.</b></div>';
		}
	} else {
		echo 'Diese Mailadresse ist nicht hinterlegt. <a href="passwort_vergessen.php">Versuche es mit einer anderen Adresse.</a>';
	}
} else {
	echo 'Passwort vergessen? Wir schicken dir gerne ein neues per Mail zu.';
	echo '<div id="passwortaenderung"><form action="" method="post">

			<table>'; 
?>
	<tr>
		<td>Mailadresse:*</td>
		<td><input type="email" name="email" id="email" required placeholder="Mailadresse*"> </td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" class="button" value="Neues Passwort anfordern"> </td>
	</tr>
	</table>

	</form>

<?php
}
include 'inc/footer.php';
?>