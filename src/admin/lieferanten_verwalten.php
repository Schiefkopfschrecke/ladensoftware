<?php

// HTML-Beginn einbetten
include '../inc/config.php';
include 'inc/functions.php';
include 'inc/login.php';
include 'inc/header.php';

$zu_loeschender_lieferant = filter_input(INPUT_GET, 'l', FILTER_VALIDATE_INT);

// Wenn diese ID grösser als Null ist, möchten wir etwas löschen

if ($zu_loeschender_lieferant > 0) {

	// Jetzt den Datensatz löschen

	$res = db_anfragen('DELETE FROM lieferanten WHERE id = ' . $zu_loeschender_lieferant);

	// Wieder zur Übersicht ohne Variablen in der Adresszeile leiten

	header('Location:' . $_SERVER['PHP_SELF']);
}

if ($_POST) {

	// Verschickte ID prüfen

	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

	// Empfangene Daten säubern

	$firmenname = filter_input(INPUT_POST, 'firmenname', FILTER_SANITIZE_STRING);
	$anrede = filter_input(INPUT_POST, 'anrede', FILTER_SANITIZE_STRING);
	$vorname = filter_input(INPUT_POST, 'vorname', FILTER_SANITIZE_STRING);
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$adrzusatz = filter_input(INPUT_POST, 'adrzusatz', FILTER_SANITIZE_STRING);
	$strasse = filter_input(INPUT_POST, 'strasse', FILTER_SANITIZE_STRING);
	$plz = filter_input(INPUT_POST, 'plz', FILTER_SANITIZE_STRING);

	$ort = filter_input(INPUT_POST, 'ort', FILTER_SANITIZE_STRING);
	$telefon = filter_input(INPUT_POST, 'telefon', FILTER_SANITIZE_STRING);
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

	$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
	$iban = filter_input(INPUT_POST, 'iban', FILTER_SANITIZE_URL);

	$lieferanten_beschrieb = filter_input(INPUT_POST, 'lieferanten_beschrieb', FILTER_SANITIZE_STRING);

	$bemerkungen = filter_input(INPUT_POST, 'bemerkungen', FILTER_SANITIZE_STRING);

	$ansprechperson_gsl = filter_input(INPUT_POST, 'ansprechperson_gsl', FILTER_SANITIZE_EMAIL);

	// Wir gehen davon aus, dass KEIN neues Bild kommt

	// Falls ein bestehender Datensatz aktualisiert werden muss

	if ($id > 0) {

		// Update

		//$datumabsolut1 = strftime('%Y%m%d', strtotime($datumabsolut));

		$sql = 'UPDATE 

						lieferanten 

					SET 

						firmenname = "' . $firmenname . '", 
						anrede = "' . $anrede . '", 
						vorname = "' . $vorname . '", 
						name = "' . $name . '", 
						adrzusatz = "' . $adrzusatz . '", 
						strasse = "' . $strasse . '", 
						plz = "' . $plz . '", 

						ort = "' . $ort . '", 
						telefon = "' . $telefon . '", 
						email = "' . $email . '", 
						url = "' . $url . '", 
						iban = "' . $iban . '", 

						lieferanten_beschrieb = "' . $lieferanten_beschrieb . '", 

						bemerkungen = "' . $bemerkungen . '", 

						ansprechperson_gsl = "' . $ansprechperson_gsl . '"

					WHERE

						id = ' . $id;

		db_anfragen($sql);
	} else if ($id === 0) {

		// Insert-Statement ausführen

		$sql = 'INSERT INTO 

						lieferanten ( firmenname, anrede, vorname, name, adrzusatz, strasse, plz, ort, telefon, email, url, iban, lieferanten_beschrieb, bemerkungen, ansprechperson_gsl )

					VALUES (

						"' . $firmenname . '", 
						"' . $anrede . '", 
						"' . $vorname . '", 
						"' . $name . '", 
						"' . $adrzusatz . '", 
						"' . $strasse . '", 
						"' . $plz . '", 

						"' . $ort . '", 
						"' . $telefon . '", 
						"' . $email . '", 
						"' . $url . '", 
						"' . $iban . '", 
						"' . $lieferanten_beschrieb . '", 

						"' . $bemerkungen . '", 

						"' . $ansprechperson_gsl . '"

					)';

		db_anfragen($sql);
	}

	// Auf die Liste weiterleiten

	header('Location:' . $_SERVER['PHP_SELF']);
}

// C: HTML-Beginn einbetten

//include 'inc/header.php';

// Dieser Inhalt steht dann in der SECTION#hauptinhalt

echo '<h1>Lieferanten verwalten</h1>';

// D: ID filtern. In der Adresszeile steht etwas wie "beitraege.php?id=123"

//	  Die ID ist danach entweder eine Ganzzahl oder FALSE

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Wir gehen davon aus, dass kein Formular gezeigt werden muss

$edit = false;

// E: ID prüfen und $edit füllen

// Falls die ID eine Ganzzahl > 0 ist, entsprechender Datensatz holen

if ($id > 0) {

	$res = db_anfragen('SELECT * FROM lieferanten WHERE id = ' . $id);

	// Diesen Datensatz als assoziativen Array bereitstellen

	$edit = mysqli_fetch_assoc($res);
}

//#####################

// Edit-angaen bearbeiten

// Sonst, falls die ID genau der Zahl 0 entspricht, soll ein neuer Datensatz vorbereitet werden

else if ($id === 0) {

	// Neuer leerer assoziativer Array vorbereiten

	$edit = array(

		'firmenname' => '',
		'anrede' => '',
		'vorname' => '',
		'name' => '',
		'adrzusatz' => '',
		'strasse' => '',
		'plz' => '',

		'ort' => '',
		'telefon' => '',
		'email' => '',

		'url' => '',
		'iban' => '',
		'lieferanten_beschrieb' => '',
		'ansprechperson_gsl' => '',
		'bemerkungen' => ''
	);
}

// F: Ist ein $edit vorhanden? JA: Formular zeigen

if ($edit) {

	// Formular

?>

	<form method="post" enctype="multipart/form-data">

		<input type="hidden" name="id" value="<?php echo $id; ?>" />

		<table>
			<tr>
				<td>Firmenname:</td>
				<td><input type="text" id="firmenname" name="firmenname" value="<?php echo htmlspecialchars(stripslashes($edit['firmenname'])); ?>"></td>
			</tr>
			<tr>
				<td>Anrede:</td>
				<td><input type="text" id="anrede" name="anrede" value="<?php echo htmlspecialchars(stripslashes($edit['anrede'])); ?>"></td>
			</tr>
			<tr>
				<td>Vorname:</td>
				<td><input type="text" id="vorname" name="vorname" value="<?php echo htmlspecialchars(stripslashes($edit['vorname'])); ?>"></td>
			</tr>
			<tr>
				<td>Name:</td>
				<td><input type="text" id="name" name="name" value="<?php echo htmlspecialchars(stripslashes($edit['name'])); ?>"></td>
			</tr>
			<tr>
				<td>Adresszusatz:</td>
				<td><input type="text" id="adrzusatz" name="adrzusatz" value="<?php echo htmlspecialchars(stripslashes($edit['adrzusatz'])); ?>"></td>
			</tr>
			<tr>
				<td>Strasse:</td>
				<td><input type="text" id="strasse" name="strasse" value="<?php echo htmlspecialchars(stripslashes($edit['strasse'])); ?>"></td>
			</tr>
			<tr>
				<td>PLZ:</td>
				<td><input type="text" id="plz" name="plz" value="<?php echo htmlspecialchars(stripslashes($edit['plz'])); ?>"></td>
			</tr>
			<tr>
				<td>Ort:</td>
				<td><input type="text" id="ort" name="ort" value="<?php echo htmlspecialchars(stripslashes($edit['ort'])); ?>"></td>
			</tr>
			<tr>
				<td>Telefon:</td>
				<td><input type="text" id="telefon" name="telefon" value="<?php echo htmlspecialchars(stripslashes($edit['telefon'])); ?>"></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><input type="text" id="email" name="email" value="<?php echo htmlspecialchars(stripslashes($edit['email'])); ?>"></td>
			</tr>
			<tr>
				<td>URL:</td>
				<td><input type="text" id="url" name="url" value="<?php echo htmlspecialchars(stripslashes($edit['url'])); ?>"></td>
			</tr>
			<tr>
				<td>IBAN:</td>
				<td><input type="text" id="iban" name="iban" value="<?php echo htmlspecialchars(stripslashes($edit['iban'])); ?>"></td>
			</tr>
			<tr>
				<td>Lieferantenbeschrieb:</td>
				<td><input type="text" id="lieferanten_beschrieb" name="lieferanten_beschrieb" value="<?php echo htmlspecialchars(stripslashes($edit['lieferanten_beschrieb'])); ?>"></td>
			</tr>
			<tr>
				<td>Ansprechperson GSL:</td>
				<td><input type="email" id="ansprechperson_gsl" name="ansprechperson_gsl" value="<?php echo htmlspecialchars(stripslashes($edit['ansprechperson_gsl'])); ?>"></td>
			</tr>
			<tr>
				<td>Bemerkungen:</td>
				<td><input type="text" id="bemerkungen" name="bemerkungen" value="<?php echo htmlspecialchars(stripslashes($edit['bemerkungen'])); ?>"></td>
			</tr>
		</table>

		<p>

			<input type="submit" value="Speichern" />

		</p>

	</form>

<?php

}

// Nein: Alle Datensätze auflisten, neuste Einträge zuoberst

else {

	// Link, um einen neuen Datensatz zu generieren

	echo '<p><a href="' . $_SERVER['PHP_SELF'] . '?id=0">Erfassen</a></p>';

	echo ' <p><input id="myInput" onkeyup="searchTable()" type="text" placeholder="Suche Lieferant" autofocus></p>';

	$res = db_anfragen('SELECT * FROM lieferanten ORDER BY id');

	echo '<table>';

	echo '</thead><tr><th data-sort="int">ID</th><th data-sort="string">Firmenname:</th><th data-sort="string">Vorname:</th><th data-sort="string">Name:</th><th data-sort="string">Ort:</th><th data-sort="string">URL:</th><th data-sort="string">Lieferantenbeschrieb:</th><th data-sort="string"></td><th data-sort="string"></th></tr></thead><tbody id="myTable">';

	// Solange es Datensätze hat

	while ($datensatz = mysqli_fetch_assoc($res)) {

		echo '<tr>';

		echo '<td>' . $datensatz['id'] . '</td>';
		echo '<td>' . $datensatz['firmenname'] . '</td>';
		echo '<td>' . $datensatz['vorname'] . '</td>';
		echo '<td>' . $datensatz['name'] . '</td>';
		echo '<td>' . $datensatz['ort'] . '</td>';
		echo '<td>';
		if ($datensatz['url']) {
			echo '<a href="' . $datensatz['url'] . '" target="_blank" title="Webseite">' . $datensatz['url'] . '</a>';
		}
		echo '</td>';
		echo '<td>' . $datensatz['lieferanten_beschrieb'] . '</td>';
		// Bearbeitenlink einfügen

		echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?id=' . $datensatz['id'] . '"><img src="../design/bearbeiten.png" title="Lieferant bearbeiten" height="20pt"></a></td>';

		// Link zum Löschen des Datensatzes. Die ID des Datensatzes wird in der Variable "l" mitgegeben

		echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?l=' . $datensatz['id'] . '" class="entfernen_prod_bestaetigen"><img src="../design/loeschen.png" title="Lieferant löschen" height="20pt"></a></td>';

		echo '</tr>';
	}

	echo '</tbody></table>';
} // Ende F

// Dieser Inhalt steht dann in der SECTION#hauptinhalt

?>
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

<?php // HTML-Ende

include 'inc/footer.php';

?>