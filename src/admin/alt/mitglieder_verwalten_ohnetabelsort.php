<?php
	use PHPMailer\PHPMailer\PHPMailer;
	require '../classes/PHPMailer-master/src/PHPMailer.php'; 
	require '../classes/PHPMailer-master/src/SMTP.php'; 
	require '../classes/PHPMailer-master/src/Exception.php';

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

	$res = db_anfragen( $sql );
	if ($res){
		$sql = 'SELECT vorname, name, email FROM mitglieder WHERE id = ' . $_POST['mitglied_id'];
		$res = db_anfragen($sql);
		$mitglieddaten = mysqli_fetch_assoc($res);
				echo '<div style="border:2px solid #e52300; padding:5px; "><b>Der Betrag ' . $_POST['betrag'] . ' wurde bei ' . $mitglieddaten['vorname'] . ' ' . $mitglieddaten['name'] . ' (' . $_POST['mitglied_id'] . ') gebucht</b></div>';
		$sql = 'SELECT SUM(preis) AS guthaben FROM buchungen_mitglieder WHERE mitglied_id = ' . $_POST['mitglied_id'] ;
	$res_kontostand = db_anfragen($sql);
	$kontostand = mysqli_fetch_assoc($res_kontostand);


		

		$mail_inhalt = 'Hoi<br>Es gab folgende Bewegung auf deinem GemeinSaftladen-Konto:<br>Betrag: CHF ' . $_POST['betrag'] . '.-<br>Betreff: ' . $_POST['betreff'] . '<br>Kontostand: CHF ' . $kontostand['guthaben'] . '<br><br>Fehler gerne an <a href="mailto:info@gemeinsaftladen.ch">info@gemeinsaftladen.ch</a> melden.<br><br>dein GemeinSaft-Team';
		
						$mail = new PHPMailer();
			$mail->isSMTP();
$mail->Host = 'romulus.metanet.ch';
$mail->SMTPAuth = true;
$mail->Username = 'info@gemeinsaftladen.ch';
$mail->Password = '5aft!Laden$';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

	$mail->setFrom('info@gemeinsaftladen.ch', 'GemeinSaftladen');
//$mail->addReplyTo('info@gemeinsaftladen.ch', 'Andreas');
		$mail->addAddress($mitglieddaten['email']);
		$mail->Subject = 'Kontobewegung GemeinSaftladen: ' . $_POST['betreff'];
		$mail->isHTML(true);
		$mail->CharSet = 'utf-8';
		$mail->Body = $mail_inhalt;
		if(!$mail->send()){
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}else{
    echo 'Mail wurde versandt';
}
	}	

}
	


	// Dieser Inhalt steht dann in der SECTION#hauptinhalt
?>
<table>
	<tr>
		<th>ID</th>
		<th>Vorname</th>
		<th>Name</th>
		<th>Telefon</th>
		<th>Email</th>
		<th>Status</th>
		<th>Kontostand</th>
		<th>bearbeiten</th>
		<!--<th>löschen</th>-->
		<th>Einzahlungsbetrag / Datum:</th>
		<th>Letzte Buchung:</th>
	</tr>
<?php $sql = 'SELECT * FROM mitglieder';

	$res = db_anfragen($sql);
	while ( $mitglieder = mysqli_fetch_assoc($res) ) {
		$sql = 'SELECT SUM(preis) AS guthaben FROM buchungen_mitglieder WHERE mitglied_id = ' . $mitglieder['id'] ;
	$res_guthaben = db_anfragen($sql);
	$guthaben = mysqli_fetch_assoc($res_guthaben);

		echo '<tr>';
		echo '<td>' . $mitglieder['id'] . '</td>';
		echo '<td>' . $mitglieder['vorname'] . '</td>';
		echo '<td>' . $mitglieder['name'] . '</td>';
		echo '<td>' . $mitglieder['telefon'] . '</td>';
		echo '<td><a href="mailto:' . $mitglieder['email'] . '">' . $mitglieder['email'] . '</a></td>';
		echo '<td>' . $mitglieder['status'] . '</td>';
		echo '<td>' . $guthaben['guthaben'] . '</td>';
		echo '<td><a href="mitglied_bearbeiten.php?mitglied_id=' . $mitglieder['id'] . '" title="Mitglied bearbeiten">bearbeiten</a></td>';
		//echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?l=' . $mitglieder['id'] . '"  class="entfernen_bestaetigen" title="Mitglied löschen"><img src="../../design/loeschen.png" height="20pt"></a></td>';
		echo '<td>';?>
		<form action="" method="post">
		<input type="hidden" name="mitglied_id" id="mitglied_id" value="<?php echo $mitglieder['id'] ?>">
		<input type="text" name="betrag" required id="betrag">
		<input type="date" name="datum" required id="datum">
		<select id="betreff" required name="betreff">
		<?php foreach ( $config['buchungstexte'] as $ein_buchungstext) {
			echo '<option value="' . $ein_buchungstext . '" ';
			echo '>' . $ein_buchungstext . '</option>';
		}
			$alle_adressen = $alle_adressen . ', ' . $mitglieder['email']?>
		</select>
			<input type="submit" class="button" value="Speichern"></form></td>
		<td><small><?php $sql='SELECT * FROM buchungen_mitglieder WHERE betreff = "Einzahlung(+)" AND mitglied_id = ' . $mitglieder['id'] . ' ORDER BY zeit DESC'; $res_letzte_buchungen = db_anfragen($sql); $letzte_buchungen = mysqli_fetch_assoc($res_letzte_buchungen); if ($letzte_buchungen['preis'] > 0) { echo 'CHF ' . $letzte_buchungen['preis'] . ' am ' . date_format(date_create($letzte_buchungen['zeit']), 'd. m. Y' ) ;} ?></small></td>
		</tr><?php }

?>


	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>Mitglied, gesperrt, ausgetreten</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
	
<?php echo $alle_adressen;
	// HTML-Ende

	include 'inc/footer.php';

?>