<?php

use PHPMailer\PHPMailer\PHPMailer;

require '../classes/PHPMailer-master/src/PHPMailer.php'; 
require '../classes/PHPMailer-master/src/SMTP.php'; 
require '../classes/PHPMailer-master/src/Exception.php';

require_once '../config/Secrets.php';

// HTML-Beginn einbetten
include '../inc/config.php';
include 'inc/login.php';
include 'inc/functions.php';
include 'inc/header.php';

if ($_POST) {

	$sql = 'INSERT INTO 
				buchungen_mitglieder (zeit, mitglied_id, preis, bucher_id, betreff )
			VALUES (
				"' . $_POST['datum'] . '",
				"' . $_POST['mitglied_id'] . '",
				"' . $_POST['betrag'] . '",
				"' . $_SESSION['user']['id'] . '",
				"' . $_POST['betreff'] . '"
				)';

	$res = db_anfragen($sql);
	if ($res) {
		$sql = 'SELECT vorname, name, email FROM mitglieder WHERE id = ' . $_POST['mitglied_id'];
		$res = db_anfragen($sql);
		$mitglieddaten = mysqli_fetch_assoc($res);
		echo '<div style="border:2px solid #e52300; padding:5px; "><b>Der Betrag ' . $_POST['betrag'] . ' wurde bei ' . $mitglieddaten['vorname'] . ' ' . $mitglieddaten['name'] . ' (' . $_POST['mitglied_id'] . ') gebucht</b></div>';
		$sql = 'SELECT SUM(preis) AS guthaben FROM buchungen_mitglieder WHERE mitglied_id = ' . $_POST['mitglied_id'];
		$res_kontostand = db_anfragen($sql);
		$kontostand = mysqli_fetch_assoc($res_kontostand);

		$mail_inhalt = 'Hoi<br>Es gab folgende Bewegung auf deinem GemeinSaftladen-Konto:<br>Betrag: CHF ' . $_POST['betrag'] . '.-<br>Betreff: ' . $_POST['betreff'] . '<br>Kontostand: CHF ' . $kontostand['guthaben'] . '<br><br>Fehler gerne an <a href="mailto:info@gemeinsaftladen.ch">info@gemeinsaftladen.ch</a> melden.<br><br>dein GemeinSaft-Team';
		
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
		$mail->addAddress($mitglieddaten['email']);
		$mail->Subject = 'Kontobewegung GemeinSaftladen: ' . $_POST['betreff'];
		$mail->isHTML(true);
		$mail->CharSet = 'utf-8';
		$mail->Body = $mail_inhalt;
		if (!$mail->send()) {
			echo 'E-Mail konnte nicht gesendet werden.';
			echo 'Fehlermeldung des Mailers: ' . $mail->ErrorInfo;
		} else {
			echo 'E-Mail wurde versandt.';
		}
	}
}

// Dieser Inhalt steht dann in der SECTION#hauptinhalt
?>
<p><input id='myInput' onkeyup='searchTable()' type='text' placeholder="Suche Mitglied" autofocus></p>
<table>
	<thead>
		<tr>
			<th data-sort="string">Vorname</th>
			<th data-sort="string">Name</th>
			<th data-sort="int">Kontostand</th>
			<!--<th>löschen</th>-->
			<th>Einzahlungsbetrag / Datum:</th>
			<th data-sort="string">Buchung der letzten 20 Tage:</th>
		</tr>
	</thead>
	<tbody id="myTable">
		<?php $sql = 'SELECT * FROM mitglieder WHERE milchabo = "Ja"';

		$res = db_anfragen($sql);
		while ($mitglieder = mysqli_fetch_assoc($res)) {
			$sql = 'SELECT SUM(preis) AS guthaben FROM buchungen_mitglieder WHERE mitglied_id = ' . $mitglieder['id'];
			$res_guthaben = db_anfragen($sql);
			$guthaben = mysqli_fetch_assoc($res_guthaben);

			echo '<tr>';
			echo '<td>' . $mitglieder['vorname'] . '</td>';
			echo '<td>' . $mitglieder['name'] . '</td>';
			echo '<td>' . $guthaben['guthaben'] . '</td>';
			echo '<td>'; ?>
			<form action="" method="post">
				<input type="hidden" name="mitglied_id" id="mitglied_id" value="<?php echo $mitglieder['id'] ?>">
				<input type="text" name="betrag" placeholder="Negativer Betrag" required id="betrag">
				<input type="date" name="datum" required id="datum">
				<input type="hidden" name="betreff" id="betreff" value="Milchprodukteabo(-)">
				<?php $alle_adressen = $alle_adressen . ', ' . $mitglieder['email'] ?>
				</select>
				<input type="submit" class="button" value="Speichern">
			</form>
			</td>
			<td><small><?php $sql = 'SELECT * FROM buchungen_mitglieder WHERE betreff = "Milchprodukteabo(-)" AND mitglied_id = ' . $mitglieder['id'] . ' ORDER BY zeit DESC';
						$res_letzte_buchungen = db_anfragen($sql);
						$letzte_buchungen = mysqli_fetch_assoc($res_letzte_buchungen);
						if (strtotime($letzte_buchungen['zeit']) > (time() - 20 * 24 * 60 * 60)) {
							echo  'CHF ' . $letzte_buchungen['preis'] . ' am ' . date_format(date_create($letzte_buchungen['zeit']), 'd. m. Y');
						} ?></small></td>
			</tr><?php }

					?>

	</tbody>
</table>
<!-- Script zum Suchen in der Tabelle -->

<script>
	function searchTable() {
		var input, filter, found, table, tr, td, i, j;
		input = document.getElementById("myInput");
		filter = input.value.toUpperCase();
		table = document.getElementById("myTable");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td");
			for (j = 0; j < td.length; j++) {
				if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
					found = true;
				}
			}
			if (found) {
				tr[i].style.display = "";
				found = false;
			} else {
				tr[i].style.display = "none";
			}
		}
	}
</script>

<?php 
echo $alle_adressen;
include 'inc/footer.php';
?>