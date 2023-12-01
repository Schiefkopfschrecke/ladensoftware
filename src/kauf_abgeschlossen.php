<?php

error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;

require 'classes/PHPMailer-master/src/PHPMailer.php';
require 'classes/PHPMailer-master/src/SMTP.php';
require 'classes/PHPMailer-master/src/Exception.php';

include 'inc/functions.php';
include 'inc/login.php';
include 'inc/config.php';
include 'config/Secrets.php';
include  'inc/header.php';

// A2: Soll ein Datensatz im Warenkorb gelöscht werden? In dem Fall ist ein Variable "l" in der 
// Adresszeile vorhanden

$sql = 'SELECT * FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
$res = db_anfragen($sql);
$mitglieddaten = mysqli_fetch_assoc($res);

?>

<div id="code">
	<p>Konto von: <a href="mein_konto.php" title="Meine Kontodaten bearbeiten"><? echo $_SESSION['user']['vorname'] . ' ' . $_SESSION['user']['name']; ?></a> / <a href="passwort_aendern.php" title="Passwort ändern">Passwort ändern</a> / <a href="letzte_einkaeufe.php" title="Letzte Kontobewegungen">Letzte Kontobewegungen</a>
</div>
<div id="status">
	<p>Guthaben in CHF:
		<? $sql = 'SELECT SUM(preis) AS guthaben FROM buchungen_mitglieder WHERE mitglied_id = ' . $_SESSION['user']['id'];
		$res = db_anfragen($sql);
		$guthaben = mysqli_fetch_assoc($res);
		echo $guthaben['guthaben'];

		// if entry in DB is done create feedback on website an by email
		if ($res) {
			// create text for website
			echo '<p>Herzlichen Dank für den Einkauf!</p>';
		}
		if ($guthaben['guthaben'] < 50) {
			$mail_inhalt = '<p>Du hast weniger als CHF 50.- Guthaben. Zahle auf folgendes Konto ein (Mindestbetrag CHF 100.-):</p><p>GemeinSaftladen<br>Spinnereiweg 15<br>3004 Bern<br>CH06 0839 0037 0225 1000 0<br>Mitteilung: Guthaben ' . $_SESSION['user']['vorname'] . ' ' . $_SESSION['user']['name'] . '</p><p>Herzlichen Dank!<p> ';
			echo '<div style="border:2px solid #e52300; padding:5px; "><b>' . $mail_inhalt . '</b></div>';

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
			$mail->addAddress($_SESSION['user']['email']);
			$mail->Subject = 'Konto aufladen ZOLLIGUET';
			$mail->isHTML(true);
			$mail->CharSet = 'utf-8';
			$mail->Body = $mail_inhalt;
			if (!$mail->send()) {
				echo 'E-Mail konnte nicht gesendet werden.';
				echo 'Fehlermeldung: ' . $mail->ErrorInfo;
			} else {
				echo 'E-Mail wurde versandt';
			}
		}

		?>

		<?php

		include  'inc/footer.php';

		?>