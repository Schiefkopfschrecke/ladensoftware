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

if ($_POST && $_POST['code'] == 8) {
	// XXX check if password is the same, agb accepted, captcha is correct and email is not ocupied yet

	// XXX encrypt password
	$pw = sha1($_POST['passwort']);

	$pw = sha1($pw . 'transformator');

	//if Form is posted, treat form
	// insert to database
	$sql = 'INSERT INTO 
				mitglieder (antragsdatum, geschlecht, vorname, name, strasse, adrzusatz, plz, ort, telefon, email, benutzername, passwort, anteilscheine, iban, wbg, agb, status, mitgliedsart, ist_admin, ist_angemeldet )
			VALUES (
				"' . date('Y-m-d H:i:s') . '",
				"' . $_POST['geschlecht'] . '",
				"' . $_POST['vorname'] . '",
				"' . $_POST['name'] . '",
				"' . $_POST['strasse'] . '",
				"' . $_POST['adrzusatz'] . '",
				"' . $_POST['plz'] . '",
				"' . $_POST['ort'] . '",
				"' . $_POST['telefon'] . '",
				"' . $_POST['email'] . '",
				"' . $_POST['benutzername'] . '",
				"' . $pw . '",
				"' . $_POST['anteilscheine'] . '",
				"' . $_POST['iban'] . '",
				"' . $_POST['wbg'] . '",
				"' . $_POST['agb'] . '",
				"Mitgliedschaft beantragt",
				"Aktivmitglied",
				"nein",
				"0"
				)';

	$res = db_anfragen($sql);

	// if entry in DB is done create feedback on website an by email
	if ($res) {
		$anteilscheine = $_POST['anteilscheine'];
		$total_anteilscheine = $anteilscheine * 150;
		$vorname = $_POST['vorname'];
		$name = $_POST['name'];
		$benutzername = $_POST['benutzername'];
		// create text for website
		echo '<div ><p><p>Liebe*r ' . $_POST['vorname'] . '</p><p>Schön hast du dich als Mitglied im GemeinSaftladen angemeldet! Hier die weiterführenden Schritte und Informationen, damit du mit dem Einkaufen loslegen kannst.</p><h2>1. Anteilschein</h2><p>Zahle bitte den Betrag für ' . $anteilscheine . ' Anteilschein(e) à CHF 150.- (Total CHF ' . $total_anteilscheine . '.-) auf folgendes Konto ein:</p><p>GemeinSaftladen<br>Spinnereiweg 15<br>3004 Bern<br>CH06 0839 0037 0225 1000 0<br><b>Mitteilung: Anteilscheine ' . $vorname . ' ' . $name . '</b></p> <p>Danach schalten wir dein Konto frei.</p><h2>2. Guthaben aufladen</h2><p>Um im GemeinSaftladen einkaufen zu können, musst du dein Guthaben-Konto aufladen. Zahle mindestens CHF 100.- auf das GemeinSaftladen Konto (Kontoangabe siehe Abschnitt 1. Anteilschein) mit der <b>Mitteilung "Guthaben ' . $vorname . ' ' . $name . '"</b>. Je höher der einbezahlte Betrag, desto weniger Arbeit haben wir. Die erste Überweisung kannst du gleichzeitig mit der Einzahlung des Anteilscheins tätigen, aber gerne als separate Zahlung.</p><h2>3. Einkaufen</h2><p><a href="https://gemeinsaftladen.ch/login.php">Logge dich in dein Konto ein.</a> Nutze dazu den Benutzername (' . $benutzername . ', nicht die Mailadresse), und dein Passwort.</p> <p>Sobald dein Konto freigeschaltet ist, siehst du dort den aktuellen Türcode. Wie das Einkaufen funktioniert, kannst du auf dem <a href="http://gemeinsaftladen.ch/downloads/Merkblatt_wie_einkaufen.pdf">Merkblatt "wie einkaufen"</a> nachlesen.</p><p>WICHTIG: Löse die «Nachbestellfunktion» in deinem Warenkorb aus, wenn du siehst, dass ein Produkt langsam zur Neige geht. So können wir sicherstellen, dass das Produkt rechtzeitig nachbestellt wird.</p><h2>4. Reinigung</h2><p>Als Mitglied hast du dich damit einverstanden erklärt, einen Halbtag pro Jahr einen Mitgliedereinsatz zu leisten. Dies soll vor allem der wöchentlichen Reinigung zu Gute kommen. Schreibe dich am Besten baldmöglichst im Reinigungsplan, der an der Türe des GemeinSaftladens hängt, ein und reserviere dir so ein bis zwei Einsätze à 1-2 Stunden in der für dich passenden Kalenderwoche. Hier findest du das <a href="https://gemeinsaftladen.ch/downloads/Merkblatt_Reinigung.pdf">Reinigungsmerkblatt</a>.</p><h2>Varia</h2><p>Weitere Merkblätter zur Rücknahme des <a href="https://gemeinsaftladen.ch/downloads/Merkblatt_Leergut.pdf">Leerguts</a> und der <a href="https://gemeinsaftladen.ch/downloads/Merkblatt_Getreidem%C3%BChle.pdf">Benutzung der Getreidemühle</a> findest du auf der Startseite der Webseite.</p><p><b>Milchprodukte:</b>Möchtest du wöchentlich Milchprodukte von der Dorfchäsi Noflen geliefert bekommen? Auf der Startseite der Webseite findest du alle Infos rund ums Abo inkl.  <a href="https://gemeinsaftladen.ch/downloads/Bestellformular_Milchprodukte.docx">Anmeldeformular</a>.</p><p><b>Produktewunsch:</b> Vermisst du etwas im Sortiment? Schreibe das gewünschte Produkt mit möglichst konkreten Angaben und deinem Namen auf ein Post-It und klebe es auf die Wunschtafel hinter der Eingangstür. Mit 8 Mitgliederstimmen innerhalb eines Monats wird das Produkt ins Auswahlverfahren aufgenommen.</p><p>Hast du überdies Lust einen Lieferantenkontakt zu übernehmen, im Vorstand mitzuwirken oder aktiv in einer Arbeitsgruppe den GemeinSaftladen weiterzuentwickeln? Dann melde dich bei uns!</p><p>Alles klar? Bei Fragen oder Rückmeldungen wende dich an <a href="mailto:info@gemeinsaftladen.ch">info@GemeinSaftladen.ch</a>.</p><p>Herzliche Grüsse<br>dein GemeinSaftladen-Team</p></p></div>';

			$mail_inhalt = '<p>Liebe*r ' . $_POST['vorname'] . '</p><p>Schön hast du dich als Mitglied im GemeinSaftladen angemeldet! Hier die weiterführenden Schritte und Informationen, damit du mit dem Einkaufen loslegen kannst.</p><h2>1. Anteilschein</h2><p>Zahle bitte den Betrag für ' . $anteilscheine . ' Anteilschein(e) à CHF 150.- (Total CHF ' . $total_anteilscheine . '.-) auf folgendes Konto ein:</p><p>GemeinSaftladen<br>Spinnereiweg 15<br>3004 Bern<br>CH06 0839 0037 0225 1000 0<br><b>Mitteilung: Anteilscheine ' . $vorname . ' ' . $name . '</b></p> <p>Danach schalten wir dein Konto frei.</p><h2>2. Guthaben aufladen</h2><p>Um im GemeinSaftladen einkaufen zu können, musst du dein Guthaben-Konto aufladen. Zahle mindestens CHF 100.- auf das GemeinSaftladen Konto (Kontoangabe siehe Abschnitt 1. Anteilschein) mit der <b>Mitteilung "Guthaben ' . $vorname . ' ' . $name . '"</b>. Je höher der einbezahlte Betrag, desto weniger Arbeit haben wir. Die erste Überweisung kannst du gleichzeitig mit der Einzahlung des Anteilscheins tätigen, aber gerne als separate Zahlung.</p><h2>3. Einkaufen</h2><p><a href="https://gemeinsaftladen.ch/login.php">Logge dich in dein Konto ein.</a> Nutze dazu den Benutzername (' . $benutzername . ', nicht die Mailadresse), und dein Passwort.</p> <p>Sobald dein Konto freigeschaltet ist, siehst du dort den aktuellen Türcode. Wie das Einkaufen funktioniert, kannst du auf dem <a href="http://gemeinsaftladen.ch/downloads/Merkblatt_wie_einkaufen.pdf">Merkblatt "wie einkaufen"</a> nachlesen.</p><p>WICHTIG: Löse die «Nachbestellfunktion» in deinem Warenkorb aus, wenn du siehst, dass ein Produkt langsam zur Neige geht. So können wir sicherstellen, dass das Produkt rechtzeitig nachbestellt wird.</p><h2>4. Reinigung</h2><p>Als Mitglied hast du dich damit einverstanden erklärt, einen Halbtag pro Jahr einen Mitgliedereinsatz zu leisten. Dies soll vor allem der wöchentlichen Reinigung zu Gute kommen. Schreibe dich am Besten baldmöglichst im Reinigungsplan, der an der Türe des GemeinSaftladens hängt, ein und reserviere dir so ein bis zwei Einsätze à 1-2 Stunden in der für dich passenden Kalenderwoche. Hier findest du das <a href="https://gemeinsaftladen.ch/downloads/Merkblatt_Reinigung.pdf">Reinigungsmerkblatt</a>.</p><h2>Varia</h2><p>Weitere Merkblätter zur Rücknahme des <a href="https://gemeinsaftladen.ch/downloads/Merkblatt_Leergut.pdf">Leerguts</a> und der <a href="https://gemeinsaftladen.ch/downloads/Merkblatt_Getreidem%C3%BChle.pdf">Benutzung der Getreidemühle</a> findest du auf der Startseite der Webseite.</p><p><b>Milchprodukte:</b>Möchtest du wöchentlich Milchprodukte von der Dorfchäsi Noflen geliefert bekommen? Auf der Startseite der Webseite findest du alle Infos rund ums Abo inkl.  <a href="https://gemeinsaftladen.ch/downloads/Bestellformular_Milchprodukte.docx">Anmeldeformular</a>.</p><p><b>Produktewunsch:</b> Vermisst du etwas im Sortiment? Schreibe das gewünschte Produkt mit möglichst konkreten Angaben und deinem Namen auf ein Post-It und klebe es auf die Wunschtafel hinter der Eingangstür. Mit 8 Mitgliederstimmen innerhalb eines Monats wird das Produkt ins Auswahlverfahren aufgenommen.</p><p>Hast du überdies Lust einen Lieferantenkontakt zu übernehmen, im Vorstand mitzuwirken oder aktiv in einer Arbeitsgruppe den GemeinSaftladen weiterzuentwickeln? Dann melde dich bei uns!</p><p>Alles klar? Bei Fragen oder Rückmeldungen wende dich an <a href="mailto:info@gemeinsaftladen.ch">info@GemeinSaftladen.ch</a>.</p><p>Herzliche Grüsse<br>dein GemeinSaftladen-Team</p>';

		// XX PHP-Mailer noch integrieren in Website

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
		//$mail->addReplyTo(Secrets::MailFrom, 'Andreas');
		$mail->addAddress($_POST['email']);
		$mail->Subject = 'Mitgliedschaft GemeinSaftladen';
		$mail->isHTML(true);
		$mail->CharSet = 'utf-8';
		$mail->Body = $mail_inhalt;
		if (!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Mail wurde versandt';
		}

		// Mail ans Team
		$betreff_saftladen = $vorname . ' ' . $name . ' angemeldet.';
		$mail_saftladen = $vorname . ';' . $name . ';' . $_POST['email'];

		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->Host = Secrets::MailHost;
		$mail->SMTPAuth = Secrets::MailSMTPAuth;
		$mail->Username = Secrets::MailUser;
		$mail->Password = Secrets::MailPassword;
		$mail->SMTPSecure = Secrets::MailSmtpSecure;
		$mail->Port = Secrets::MailPort;
	
		$mail->setFrom(Secrets::MailFrom, Secrets::MailFromName);
		//$mail->addReplyTo(Secrets::MailFrom, 'Andreas');
		$mail->addAddress('info@gemeinsaftladen.ch');
		$mail->addCC('a.koenig@immerda.ch');
		$mail->Subject = $betreff_saftladen;
		$mail->isHTML(true);
		$mail->Body = $mail_saftladen;
		if (!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Mail wurde versandt';
		}

		//			mail('a.koenig@immerda.ch',$betreff_saftladen,$mail_saftladen);
	};

	// If no form is sent show the content with the empty form
} else if ($_POST && $_POST['code'] !== 8) {
	echo '<div style="border:2px solid #e52300; padding:5px; "><b>Die richtige Antwort auf die Rechenaufgabe ist 8 (als Zahl)</b></div>';
} else {
?>
		<h1>Was bedeutet die Mitgliedschaft</h1>
		<p>Als Mitglied des Vereins profitierst du von:</p>
			<ul>
				<li>24/7-Zugang zum GemeinSaftladen</li>
				<li>Vorzugspreise bei den Produkten</li>
			</ul>
			<p>Du erklärst dich für Einverstanden mit:</p>
			<ul>
				<li><b>Mitgestaltung</b> des GemeinSaftladens (min. 4 Stunden pro Jahr), insbesondere das Sauberhalten des Raumes (siehe <a href="downloads/Merkblatt_Reinigung.pdf" title="Merkblatt Reinigung" target="_blank">Merkblatt Reinigung</a>).</li>
				<li>Du bist über 18 Jahre alt.</li>
				<li>Es kann nur auf <b>Voreinzahlung</b> eingekauft werden. Bei Schulden von CHF 50.- wird eine Mahnung verschickt (ab der 2. Mahnung CHF 5.- Gebühr).</li>
				<li>Du löst <b>mindestens einen Anteilschein</b> (CHF 150.-). Bei einem Haushalt von mehr als 4 Personen begrüssen wir es, wenn ihr zusätzliche Anteilsscheine löst. Wir wollen euch das Geld bei einem Austritt wenn immer möglich zurückzahlen.</li>
				<li>Du nimmst zur Kenntnis, dass der Verein <b>keine Haftung</b> bezüglich fehlerhaften Produkten übernimmt.</li>
			</ul>
			<p>Die Mitgliedschaft kostenlos. Im Moment versuchen wir die Fixkosten von ca. 300.-/Monat (Mietzins etc.) durch eine Marge (14%) auf den Produktepreisen zu decken.</p>
			<p>Da unser Laden klein ist und wir die lokale Vernetzung fördern wollen, sind wir in erster Linie für Belebende der Felsenau und dem Rossfeld da. Falls du in einem anderen Quartier wohnst, <a href="mailto:info@gemeinsaftladen.ch">schreibe uns</a> doch kurz deine Motivation, warum du bei uns Mitglied werden willst. Wir machen Ausnahmen :-).</p>
	<p>Hier findest du die <a href="downloads/Statuten_Gemeinsaftladen.pdf" titel="Statuten" target="_blank">Statuten</a>.</p>

	<h1>Wie werde ich Mitglied?</h1>
	<ol>
		<li>Fülle das untenstehende Formular aus.</li>
		<li>Zahle die Anteilscheine ein.</li>
		<li>Zahle einen Betrag ein, von welchem deine Einkäufe abgezogen werden (Mindestbetrag CHF 100.-).</li>
		<li>Sobald du als Mitglied freigeschaltet bist, kannst du dich auf der Webseite einloggen und im GemeinSaftladen einkaufen.</li>
	</ol>
	<div id="anmeldung">
		<form action="" method="post">

			<h1>Ja, ich werde Mitglied</h1>
			<table>
				<tr>
					<td>Anrede:</td>
					<td><input type="radio" name="geschlecht" id="geschlecht" value="Frau" required> Frau <input type="radio" name="geschlecht" id="geschlecht" value="Herr" required> Herr <input type="radio" name="geschlecht" id="geschlecht" value="Neutral" required> Anderes </td>
				</tr>
				<tr>
					<td>Vorname:*</td>
					<td><input type="text" name="vorname" id="vorname" required placeholder="Vorname*"> </td>
				</tr>
				<tr>
					<td>Name:*</td>
					<td><input type="text" name="name" id="name" required placeholder="Name*"> </td>
				</tr>
				<tr>
					<td>Strasse:*</td>
					<td><input type="text" name="strasse" id="strasse" required placeholder="Strasse/Nr.*"> </td>
				</tr>
				<tr>
					<td>Adresszusatz:</td>
					<td><input type="text" name="adrzusatz" id="adrzusatz" placeholder="Adresszusatz"> </td>
				</tr>
				<tr>
					<td>PLZ:*</td>
					<td><input type="text" name="plz" id="plz" required placeholder="PLZ*"> </td>
				</tr>
				<tr>
					<td>Ort:*</td>
					<td><input type="text" name="ort" id="ort" required placeholder="Ort*"> </td>
				</tr>
				<tr>
					<td>Telefon:*</td>
					<td><input type="text" name="telefon" id="telefon" required placeholder="Telefon*"> </td>
				</tr>
				<tr>
					<td>Email:*</td>
					<td><input type="email" name="email" id="email" required placeholder="Email*"> </td>
				</tr>
				<tr>
					<td>Benutzername:*</td>
					<td><input type="benutzername" name="benutzername" id="benutzername" required placeholder="Benutzername*"> </td>
				</tr>
				<script>
					/*Überprüfen ob Passwort das selbe ist*/
					var check = function() {
						if (document.getElementById('passwort').value ==
							document.getElementById('passwort_wiederholung').value) {
							document.getElementById('message').style.color = 'green';
							document.getElementById('message').innerHTML = 'gleiches Passwort';
						} else {
							document.getElementById('message').style.color = 'red';
							document.getElementById('message').innerHTML = 'nicht gleiches Passwort';
						}
					}
				</script>
				<tr>
					<td>Passwort:*</td>
					<td><input type="password" name="passwort" id="passwort" required placeholder="Passwort*" onkeyup="check();"> </td>
				</tr>
				<tr>
					<td>Passwortwiederholung:*</td>
					<td><input type="password" name="passwort_wiederholung" id="passwort_wiederholung" required placeholder="Wiederhole das Passwort*" onkeyup="check();"> <span id="message"></span></td>
				</tr>

				<tr>
					<td>Anzahl Anteilscheine:</td>
					<td><input type="number" name="anteilscheine" id="anteilscheine" value="1"> </td>
				</tr>
				<tr>
					<td>IBAN-Nummer:</td>
					<td><input type="text" name="iban" id="iban" placeholder="IBAN-Nummer"> </td>
				</tr>
				<tr>
					<td>Genossenschafter*in bei der WBG Via Felsenau:*</td>
					<td><input type="radio" name="wbg" id="wbg" value="Ja" required> Ja <input type="radio" name="wbg" id="wbg" value="Nein" required> Nein </td>
				</tr>
				<tr>
					<td>Ich akzeptiere die Statuten & AGB:*</td>
					<td><input type="checkbox" name="agb" id="agb" value="Ja" required>* Ich bin mit den AGB einverstanden </td>
				</tr>
				<tr>
					<td>Ich bin kein Computer und kann rechnen:*</td>
					<td><input type="text" name="code" id="code" required placeholder="Was gibt 3+5?"> </td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button" value="Absenden"> </td>
				</tr>
			</table>

		</form>
	</div>

<?php
}

include 'inc/footer.php';
?>