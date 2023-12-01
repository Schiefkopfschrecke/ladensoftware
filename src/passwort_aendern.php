<?php

error_reporting(E_ALL);

// include functions, configurations and header
include  'inc/functions.php';
include  'inc/login.php';
include  'inc/config.php';
include  'inc/header.php';

// Start treating form
// check if form is posted

if ($_POST) {
	// Altes Passwort von DB aufrufen
	$sql = 'SELECT passwort FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
	$res = db_anfragen($sql);
	$mitglieddaten = mysqli_fetch_assoc($res);

	// altes Passwort aus Eingabeformular verschlüsseln
	$pw = sha1($_POST['passwort']);
	$pw = sha1($pw . 'transformator');

	// Beide alte Passwörter vergleichen, wenn korrekt
	if ($pw == $mitglieddaten['passwort']) {
		// Neues Passwort verschlüsseln
		$pw_neu = sha1($_POST['neues_passwort']);
		$pw_neu = sha1($pw_neu . 'transformator');

		// Neues Passwort wird in DB gespeichert
		$sql = 'UPDATE 
						mitglieder 
					SET
						passwort = "' . $pw_neu . '" WHERE id = ' . $_SESSION['user']['id'];
		$res = db_anfragen($sql);

		// if entry in DB is done create feedback on website 
		if ($res) {
			// create text for website
			echo '<div style="border:2px solid #e52300; padding:5px; "><b>Das Passwort wurden geändert</b></div>';
		}
	}
}
echo '
<div id="passwortaenderung"><form action="" method="post">

			<table>'; 
?>
<script>
	/*Überprüfen ob Passwort das selbe ist*/
	var check = function() {
		if (document.getElementById('neues_passwort').value ==
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
	<td>Altes Passwort:*</td>
	<td><input type="password" name="passwort" id="passwort" required placeholder="altes Passwort*"> </td>
</tr>
<tr>
	<td>Neues Passwort:*</td>
	<td><input type="password" name="neues_passwort" id="neues_passwort" required placeholder="neues Passwort*" onkeyup="check();"> </td>
</tr>
<tr>
	<td>Neues Passwort wiederholen:*</td>
	<td><input type="password" name="passwort_wiederholung" id="passwort_wiederholung" required placeholder="Wiederhole das neue Passwort*" onkeyup="check();"> <span id="message"></span></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" class="button" value="Passwort ändern"> </td>
</tr>
</table>

</form>
</div>

<?php
include  'inc/footer.php';
?>