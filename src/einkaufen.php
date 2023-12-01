<?php
error_reporting(E_ALL);

include 'inc/functions.php';
include 'inc/login.php';
include 'inc/config.php';
include 'inc/header.php';

use PHPMailer\PHPMailer\PHPMailer;

require 'classes/PHPMailer-master/src/PHPMailer.php';
require 'classes/PHPMailer-master/src/SMTP.php';
require 'classes/PHPMailer-master/src/Exception.php';

require_once 'config/Secrets.php';

// A2: Soll ein Datensatz im Warenkorb gelöscht werden? In dem Fall ist ein Variable "l" in der 
// Adresszeile vorhanden

$zu_loeschendes_produkt = filter_input(INPUT_GET, 'l', FILTER_VALIDATE_INT);
$nachsbestellendes_produkt = filter_input(INPUT_GET, 'nb', FILTER_VALIDATE_INT);

// Wenn diese ID grösser als Null ist, möchten wir etwas löschen

if ($zu_loeschendes_produkt > 0) {

	// Jetzt den Datensatz löschen

	$res = db_anfragen('DELETE FROM warenkorb WHERE id = ' . $zu_loeschendes_produkt);

	// Wieder zur Übersicht ohne Variablen in der Adresszeile leiten

	header('Location:' . $_SERVER['PHP_SELF']);
}

if ($nachsbestellendes_produkt > 0) {
	$sql = 'SELECT produktname, lieferant_id FROM produkte WHERE id = ' . $nachsbestellendes_produkt;
	$res = db_anfragen($sql);
	$produktinfo = mysqli_fetch_assoc($res);
	$sql = 'SELECT firmenname, ansprechperson_gsl FROM lieferanten WHERE id = ' . $produktinfo['lieferant_id'];
	$res_lieferant = db_anfragen($sql);
	$lieferanteninfo = mysqli_fetch_assoc($res_lieferant);
	$subject = 'Produkt Nr. ' . $nachsbestellendes_produkt . ' ' . $produktinfo['produktname'] . ' von ' . $lieferanteninfo['firmenname'] . ' nachbestellen';
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = Secrets::MailHost;
	$mail->SMTPAuth = Secrets::MailSMTPAuth;
	$mail->Username = Secrets::MailUser;
	$mail->Password = Secrets::MailPassword;
	$mail->SMTPSecure = Secrets::MailSmtpSecure;
	$mail->Port = Secrets::MailPort;

	$mail->setFrom(Secrets::MailFrom, Secrets::MailFromName);
	$mail->addAddress($lieferanteninfo['ansprechperson_gsl']);
	$mail->addCC('einkauf@zolliguet.ch');
	$mail->Subject = $subject;
	$mail->isHTML(true);
	$mail->CharSet = 'utf-8';
	$mail->Body = $subject;
	if (!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Mail wurde versandt';
	}
	// Wieder zur Übersicht ohne Variablen in der Adresszeile leiten

	//header( 'Location:' . $_SERVER['PHP_SELF'] );

}

if ($_POST) {
	$mitglied_id = filter_input(INPUT_POST, 'mitglied_id', FILTER_VALIDATE_INT);

	// Empfangene Daten säubern

	$produkt_id = filter_input(INPUT_POST, 'produkt_id', FILTER_SANITIZE_NUMBER_INT);

	$menge = filter_input(INPUT_POST, 'menge', FILTER_SANITIZE_NUMBER_INT);
	$sql = 'SELECT vp_pro_einheit, rabatt FROM produkte WHERE id=' . $produkt_id;
	$res = db_anfragen($sql);
	$produkte = mysqli_fetch_assoc($res);
	$vp_pro_einheit = $produkte['vp_pro_einheit'];
	$rabatt = $produkte['rabatt'];
	// Marge abrufen
	$sql = 'SELECT marge FROM parameter WHERE id=1';
	$res_marge = db_anfragen($sql);
	$marge =  mysqli_fetch_assoc($res_marge);
	$marge = $marge['marge'];
	$preis = number_format($menge * $vp_pro_einheit * $rabatt * $marge, 2, '.', '');

	// XX Einfügen von Kontrolle der Eingaben und Reinigung

	$sql = 'INSERT INTO 
				warenkorb (zeit, mitglied_id, produkt_id, menge, preis )
			VALUES (
				"' . date('Y-m-d H:i:s') . '",
				"' . $mitglied_id . '",
				"' . $produkt_id . '",
				"' . $menge . '",
				"' . $preis . '"
				)';

	$res = db_anfragen($sql);

	// if entry in DB is done create feedback on website an by email
	if ($res) {
		// create text for website
		echo '<div style="border:2px solid #e52300; padding:5px; "><b>In Warenkorb eingefügt</b></div>';
	}
}
$sql = 'SELECT * FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
$res = db_anfragen($sql);
$mitglieddaten = mysqli_fetch_assoc($res);
?>

<div id="code">
	<p>Konto von: <a href="mein_konto.php" title="Meine Kontodaten bearbeiten"><? echo $_SESSION['user']['vorname'] . ' ' . $_SESSION['user']['name']; ?></a> / <a href="letzte_einkaeufe.php" title="Letzte Kontobewegungen">Letzte Kontobewegungen</a>
		<? if ($mitglieddaten['status'] == "berechtigt") {
			echo '<br>Der aktuelle Türcode ist: ';
			// WLAN-PW abrufen
			$sql = 'SELECT tuercode, wlan FROM parameter WHERE id=1';
			$res_parameter = db_anfragen($sql);
			$parameter =  mysqli_fetch_assoc($res_parameter);
			echo $parameter['tuercode'];
			echo '<br>WLAN-Passwort: ' . $parameter['wlan'] . '</p>';
		} ?>
</div>
<div id="status">
	<p>Dein Status ist: <? echo $mitglieddaten['status']; ?> <br>
		Guthaben in CHF:
		<? $sql = 'SELECT SUM(preis) AS guthaben FROM buchungen_mitglieder WHERE mitglied_id = ' . $_SESSION['user']['id'];
		$res = db_anfragen($sql);
		$guthaben = mysqli_fetch_assoc($res);
		echo $guthaben['guthaben'];
		?>
	</p>
</div>
<? if ($mitglieddaten['status'] == "berechtigt") {
	echo '<div id="kaufen"><form action="menge.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="mitglied_id" id="mitglied_id" hidden value="' . $_SESSION['user']['id'] . '">
				<input type="number" name="produkt_id" id="produkt_id" required placeholder="Produktnummer" autocomplete="off" autofocus>
				<input type="submit" class="button"';
	if ($_SESSION['user']['id'] == 14) {
		echo ' style="background: #FF00CE" ';
	}
	echo '   value="Produkt wählen">

        </p>

    </form></div>
<div id="kauf_abschliessen"><form action="kauf_abbuchen.php" method="post" enctype="multipart/form-data">';
	$sql = 'SELECT SUM(preis) AS total FROM warenkorb WHERE mitglied_id = ' . $_SESSION['user']['id'] . ' AND eingekauft = 0';
	$res = db_anfragen($sql);
	$summe = mysqli_fetch_assoc($res);

	echo '<input type="hidden" name="mitglied_id" id="mitglied_id" hidden value="' . $_SESSION['user']['id'] . '">
				<input type="hidden" name="total" id="total" hidden value="' . $summe['total'] . '"> <!-- XX mit Quittung 1 für ja 0 für nein-->
				<input type="submit" class="button" id="einkauf_abschliessen" value="Einkauf abschliessen">

        </p>

    </form></div>';
} ?>

<div id="warenkorb">
	<table>
		<tr>
			<th>Produkt</th>
			<th>Menge</th>
			<th>Preis</th>
			<th>Nachbestellung<br>nötig?</th>
			<th>löschen</th>
		</tr>

		<?php
		$sql = 'SELECT * FROM warenkorb WHERE mitglied_id = ' . $_SESSION['user']['id'] . ' AND eingekauft = 0 ORDER BY zeit DESC';

		$res_warenkorb = db_anfragen($sql);
		echo '<div id="warenkorb">';

		while ($warenkorb = mysqli_fetch_assoc($res_warenkorb)) {
			$sql = 'SELECT produktname, einheit, rabatt FROM produkte WHERE id = ' . $warenkorb['produkt_id'];
			$res = db_anfragen($sql);
			$produktinfo = mysqli_fetch_assoc($res);
			echo '<tr><td>' . $warenkorb['produkt_id'] . ' ' . $produktinfo['produktname'] . '</td><td>' . number_format($warenkorb['menge'], 0, '.', '')  . ' ' . $produktinfo['einheit'] . '</td><td>' . $warenkorb['preis'];
			if ($produktinfo['rabatt'] < 1) {
				echo '<br>auf ' . $produktinfo['rabatt'] * 100 . '% reduziert';
			}
			echo '</td><td><a href="' . $_SERVER['PHP_SELF'] . '?nb=' . $warenkorb['produkt_id'] . '"  class="nachbestellen_bestaetigen" title="Produkt nachbestellen">Ja!</a></td><td><a href="' . $_SERVER['PHP_SELF'] . '?l=' . $warenkorb['id'] . '"  class="entfernen_bestaetigen" title="Produkt entfernen"><img src="design/loeschen.png" height="20pt"></a> </td>';
			// Text in Bestätigungsfenster in js/meine-cms-scripts.js gespeichert
			$total = $total + $warenkorb['preis'];
		}

		echo '</div>';

		?>

		<tr>
			<td><b>Total</b></td>
			<td></td>
			<td><b>CHF <? echo number_format($total, 2, '.', '\''); ?></b></td>
			<td></td>
			<td></td>
		</tr>

	</table>

</div>

<?php
include 'inc/footer.php';
?>