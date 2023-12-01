<?php

error_reporting(E_ALL);

// include functions, configurations and header
include 'inc/functions.php';
include 'inc/login.php';
include 'inc/config.php';
include 'inc/header.php';

// Start treating form
// check if form is posted

if ($_POST) {

	//if Form is posted, treat form
	// insert to database
	$sql = 'UPDATE 
				mitglieder 
			SET
				geschlecht = "' . $_POST['geschlecht'] . '", vorname = "' . $_POST['vorname'] . '", name = "' . $_POST['name'] . '", strasse ="' . $_POST['strasse'] . '", adrzusatz = "' . $_POST['adrzusatz'] . '", plz = "' . $_POST['plz'] . '", ort = "' . $_POST['ort'] . '", telefon = "' . $_POST['telefon'] . '", email = "' . $_POST['email'] . '", benutzername = "' . $_POST['benutzername'] . '", iban = "' . $_POST['iban'] . '", wbg = "' . $_POST['wbg'] . '" WHERE id = ' . $_SESSION['user']['id'];

	$res = db_anfragen($sql);

	// if entry in DB is done create feedback on website an by email
	if ($res) {
		// create text for website
		echo '<div style="border:2px solid #e52300; padding:5px; "><b>Deine Daten wurden geändert</b></div>';
	};
}
$sql = 'SELECT * FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
$res = db_anfragen($sql);
$mitglieddaten = mysqli_fetch_assoc($res);
echo '<div id="kontoangaben"><form method="post" action="">
			<table>
			<tr>
				<td>Mitglieschaftsstatus:</td>
				<td>' . $mitglieddaten['status'] . '</td></tr>
			<tr>
				<td>Guthaben:</td>
				<td>';
$sql = 'SELECT SUM(preis) AS guthaben FROM buchungen_mitglieder WHERE mitglied_id = ' . $_SESSION['user']['id'];
$res = db_anfragen($sql);
$guthaben = mysqli_fetch_assoc($res);
echo $guthaben['guthaben'];
echo '</td></tr>
			<tr>
				<td>Mitgliedernummer</td>
				<td>' . $_SESSION['user']['id'] . '</td></tr>
			<tr>
				<td><a href="passwort_aendern.php" title="Passwort ändern">Passwort ändern</a></td>
				<td></td></tr>
			<tr>
				<td colspan="2"><a href="letzte_einkaeufe.php" title="Letzte Kontobewegungen">Letzte Kontobewegungen ansehen</a></td>
				</tr>
			<tr>
				<td>Anrede:</td>
				<td><input type="radio" name="geschlecht" id="geschlecht" value="Frau" ';
if ($mitglieddaten['geschlecht'] == 'Frau') {
	echo 'checked';
}
echo ' required> Frau <input type="radio" name="geschlecht" id="geschlecht" value="Herr" ';
if ($mitglieddaten['geschlecht'] == 'Herr') {
	echo 'checked';
}
echo '  required> Herr <input type="radio" name="geschlecht" id="geschlecht" value="Neutral"  ';
if ($mitglieddaten['geschlecht'] == 'Neutral') {
	echo 'checked';
}
echo ' required> Anderes </td></tr>
			<tr>
				<td>Vorname:*</td>
				<td><input type="text" name="vorname" id="vorname" required placeholder="Vorname*" value="' . $mitglieddaten['vorname'] . '"> </td></tr>
			<tr>
				<td>Name:*</td>
				<td><input type="text" name="name" id="name" required placeholder="Name*" value="' . $mitglieddaten['name'] . '"> </td></tr>
			<tr>
				<td>Strasse:*</td>
				<td><input type="text" name="strasse" id="strasse" required placeholder="Strasse/Nr.*" value="' . $mitglieddaten['strasse'] . '"> </td></tr>
			<tr>
				<td>Adresszusatz:</td>
				<td><input type="text" name="adrzusatz" id="adrzusatz" placeholder="Adresszusatz" value="' . $mitglieddaten['adrzusatz'] . '"> </td></tr>
			<tr>
				<td>PLZ:*</td>
				<td><input type="text" name="plz" id="plz" required placeholder="PLZ*" value="' . $mitglieddaten['plz'] . '"> </td></tr>
			<tr>
				<td>Ort:*</td>
				<td><input type="text" name="ort" id="ort" required placeholder="Ort*" value="' . $mitglieddaten['ort'] . '"> </td></tr>
			<tr>
				<td>Telefon:*</td>
				<td><input type="text" name="telefon" id="telefon" required placeholder="Telefon*" value="' . $mitglieddaten['telefon'] . '"> </td></tr>
			<tr>
				<td>Email:*</td>
				<td><input type="email" name="email" id="email" required placeholder="Email*" value="' . $mitglieddaten['email'] . '"> </td></tr>
			<tr>
				<td>Benutzername:*</td>
				<td><input type="benutzername" name="benutzername" id="benutzername" required placeholder="Benutzername*" value="' . $mitglieddaten['benutzername'] . '"> </td></tr>
			<!--<tr>
				<td>Passwort:*</td>
				<td><input type="password" name="passwort" id="passwort" required placeholder="Passwort*"> </td></tr>-->
			<tr>
				<td>IBAN-Nummer:</td>
				<td><input type="text" name="iban" id="iban" placeholder="IBAN-Nummer" value="' . $mitglieddaten['iban'] . '"> </td></tr>
			<tr>
				<td>Genossenschafter*in bei der WBG Via Felsenau:*</td>
				<td><input type="radio" name="wbg" id="wbg" value="Ja"  ';
if ($mitglieddaten['wbg'] == 'Ja') {
	echo 'checked';
}
echo ' required> Ja <input type="radio" name="wbg" id="wbg" value="Nein"  ';
if ($mitglieddaten['wbg'] == 'Nein') {
	echo 'checked';
}
echo ' required> Nein  </td></tr>
			<tr>
				<td></td>
				<td><input type="submit" class="button" value="Ändern"> </td></tr>
	</table>

    </form></div>';

include 'inc/footer.php';
