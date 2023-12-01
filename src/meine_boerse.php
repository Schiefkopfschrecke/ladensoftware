<?php

error_reporting(E_ALL);

// include functions, configurations and header
include 'inc/functions.php';
include 'inc/login.php';
include 'inc/config.php';
include 'inc/header.php';

if ($_GET) {
	// Produkt löschen
	$zu_loeschendes_produkt = filter_input(INPUT_GET, 'l', FILTER_VALIDATE_INT);
	// Wenn diese ID grösser als Null ist, möchten wir etwas löschen

	if ($zu_loeschendes_produkt > 0) {

		// Jetzt den Datensatz löschen

		$res = db_anfragen('DELETE FROM gegenstaende WHERE id = ' . $zu_loeschendes_produkt);

		// Wieder zur Übersicht ohne Variablen in der Adresszeile leiten

		header('Location:' . $_SERVER['PHP_SELF']);
	}
}
// Ende Produkt löschen

if ($_POST) {

	// Verschickte ID prüfen

	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

	// Empfangene Daten säubern 

	$bezeichnung = filter_input(INPUT_POST, 'bezeichnung', FILTER_SANITIZE_STRING);
	$kategorie = filter_input(INPUT_POST, 'kategorie', FILTER_SANITIZE_STRING);

	$beschrieb = filter_input(INPUT_POST, 'beschrieb', FILTER_SANITIZE_STRING);
	$bedingungen = filter_input(INPUT_POST, 'bedingungen', FILTER_SANITIZE_STRING);
	$kontakt_name = filter_input(INPUT_POST, 'kontakt_name', FILTER_SANITIZE_STRING);
	$kontakt_telefon = filter_input(INPUT_POST, 'kontakt_telefon', FILTER_SANITIZE_STRING);
	$kontakt_email = filter_input(INPUT_POST, 'kontakt_email', FILTER_SANITIZE_STRING);

	// Falls ein bestehender Datensatz aktualisiert werden muss

	if ($id > 0) {

		// Update

		//$datumabsolut1 = strftime('%Y%m%d', strtotime($datumabsolut));

		$sql = 'UPDATE 

						gegenstaende 

					SET 
						bezeichnung = "' . $bezeichnung . '", 
						kategorie = "' . $kategorie . '", 

						beschrieb = "' . $beschrieb . '", 
						bedingungen = "' . $bedingungen . '", 
						kontakt_name = "' . $kontakt_name . '", 
						kontakt_telefon = "' . $kontakt_telefon . '", 
						kontakt_email = "' . $kontakt_email . '"

					WHERE

						id = ' . $id;

		db_anfragen($sql);
	} else if ($id === 0) {

		// Insert-Statement ausführen

		$sql = 'INSERT INTO 

						gegenstaende ( mitglied_id, bezeichnung, kategorie, beschrieb, bedingungen, kontakt_name, kontakt_telefon, kontakt_email )

					VALUES (
						"' . $_SESSION['user']['id'] . '",
						"' . $bezeichnung . '", 
						"' . $kategorie . '", 

						"' . $beschrieb . '", 
						"' . $bedingungen . '", 
						"' . $kontakt_name . '", 
						"' . $kontakt_telefon . '", 
						"' . $kontakt_email . '"

					)';

		db_anfragen($sql);
	}

	// Auf die Liste weiterleiten

	header('Location:meine_boerse.php');
}

// C: HTML-Beginn einbetten

// Dieser Inhalt steht dann in der SECTION#hauptinhalt

// D: ID filtern. In der Adresszeile steht etwas wie "beitraege.php?id=123"

//	  Die ID ist danach entweder eine Ganzzahl oder FALSE

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Wir gehen davon aus, dass kein Formular gezeigt werden muss

$edit = false;

// E: ID prüfen und $edit füllen

// Falls die ID eine Ganzzahl > 0 ist, entsprechender Datensatz holen

if ($id > 0) {

	$res = db_anfragen('SELECT * FROM gegenstaende WHERE id = ' . $id);

	// Diesen Datensatz als assoziativen Array bereitstellen

	$edit = mysqli_fetch_assoc($res);
}

//#####################

// Edit-angaen bearbeiten

// Sonst, falls die ID genau der Zahl 0 entspricht, soll ein neuer Datensatz vorbereitet werden

else if ($id === 0) {

	// Neuer leerer assoziativer Array vorbereiten
	$sql = 'SELECT id, vorname, name, telefon, email FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
	$res = db_anfragen($sql);
	$mitglieddaten = mysqli_fetch_assoc($res);
	$edit = array(

		'bezeichnung' => '',
		'kategorie' => '',
		'beschrieb' => '',
		'bedingungen' => '',
		'kontakt_name' => $mitglieddaten['vorname'] . ' ' . $mitglieddaten['name'],
		'kontakt_telefon' => $mitglieddaten['telefon'],
		'kontakt_email' => $mitglieddaten['email']
	);
}

// F: Ist ein $edit vorhanden? JA: Formular zeigen

if ($edit) {

	// Formular

?>

	<form method="post" enctype="multipart/form-data">
		<h2>Gegenstand erfassen/ändern</h2>
		<input type="hidden" name="id" value="<?php echo $id; ?>" />

		<table>
			<tr>
				<td>Bezeichnung:*</td>
				<td><input type="text" name="bezeichnung" id="bezeichnung" required placeholder="Bezeichnung*" value="<?php echo htmlspecialchars(stripslashes($edit['bezeichnung'])); ?>"> </td>
			</tr>

			<tr>
				<td>Kategorie:*</td>
				<td><select id="kategorie" name="kategorie"><?php
															foreach ($config['ausleihkategorien'] as $eine_kategorie) {
																echo '<option value="' . $eine_kategorie . '" ';
																if ($eine_kategorie == $edit['kategorie']) {
																	echo 'selected';
																}
																echo '>' . $eine_kategorie . '</option>';
															} ?>
						<option value="versteckt" <?php if ($edit['kategorie'] == 'versteckt') {
														echo ' selected';
													} ?>>versteckt</option>
					</select></td>
			</tr>
			<tr>
				<td>Beschrieb:*</td>
				<td><input type="text" name="beschrieb" id="beschrieb" required placeholder="Beschrieb*" value="<?php echo htmlspecialchars(stripslashes($edit['beschrieb'])); ?>"> </td>
			</tr>
			<tr>
				<td>Bedingungen:*</td>
				<td><input type="text" name="bedingungen" id="bedingungen" required placeholder="Bedingungen*" value="<?php echo htmlspecialchars(stripslashes($edit['bedingungen'])); ?>"> </td>
			</tr>
			<tr>
				<td>(Vor-)Name:*</td>
				<td><input type="text" name="kontakt_name" id="kontakt_name" required placeholder="Kontakt Name*" value="<?php echo htmlspecialchars(stripslashes($edit['kontakt_name'])); ?>"> </td>
			</tr>
			<tr>
				<td>Telefon:*</td>
				<td><input type="text" name="kontakt_telefon" id="kontakt_telefon" required placeholder="Bedingungen*" value="<?php echo htmlspecialchars(stripslashes($edit['kontakt_telefon'])); ?>"><br>falls Kontakt nur über Mail: "-" einfüllen </td>
			</tr>
			<tr>
				<td>Email:*</td>
				<td><input type="text" name="kontakt_email" id="kontakt_email" required placeholder="Kontaktemail*" value="<?php echo htmlspecialchars(stripslashes($edit['kontakt_email'])); ?>"><br>falls Kontakt nur über Telefon: "-" einfüllen </td>
			</tr>
		</table>

		<p>

			<input type="submit" value="Speichern" />&nbsp;&nbsp;&nbsp;<a href="meine_boerse.php"><input type="button" value="Abbrechen" action="meine_boerse.php" /></a>

		</p>

	</form>
	</div>

<?php
}

// Nein: Alle Datensätze auflisten, neuste Einträge zuoberst
else {
?>

	<!-- Anfang Tabellensortierungstool Studpid Table -->
	<script src="js/stupid-table-plugin/stupidtable.js?dev"></script>
	<script>
		$(function() {
			// Helper function to convert a string of the form "Mar 15, 1987" into a Date object.
			var date_from_string = function(str) {
				var months = ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"];
				var pattern = "^([a-zA-Z]{3})\\s*(\\d{1,2}),\\s*(\\d{4})$";
				var re = new RegExp(pattern);
				var DateParts = re.exec(str).slice(1);

				var Year = DateParts[2];
				var Month = $.inArray(DateParts[0].toLowerCase(), months);
				var Day = DateParts[1];

				return new Date(Year, Month, Day);
			}

			var table = $("table").stupidtable({
				"date": function(a, b) {
					// Get these into date objects for comparison.
					aDate = date_from_string(a);
					bDate = date_from_string(b);
					return aDate - bDate;
				}
			});

			table.on("beforetablesort", function(event, data) {
				// Apply a "disabled" look to the table while sorting.
				// Using addClass for "testing" as it takes slightly longer to render.
				$("#msg").text("Sorting...");
				$("table").addClass("disabled");
			});

			table.on("aftertablesort", function(event, data) {
				// Reset loading message.
				$("#msg").html("&nbsp;");
				$("table").removeClass("disabled");

				var th = $(this).find("th");
				th.find(".arrow").remove();
				var dir = $.fn.stupidtable.dir;

				var arrow = data.direction === dir.ASC ? "&uarr;" : "&darr;";
				th.eq(data.column).append('<span class="arrow">' + arrow + '</span>');
			});
		});
	</script>
	<!-- Ende Tabellensortierungstool Studpid Table -->

	<?php
	echo '<p align="right"><a href="ausleihboerse.php">Leihbörse</a></p><h2>Leihgegenstände verwalten</h2><p><a href="' . $_SERVER['PHP_SELF'] . '?id=0">Erfassen</a></p>';
	?>
	<p><input id='myInput' onkeyup='searchTable()' type='text' placeholder="Suche Leihgegenstand" autofocus></p>
	<?php
	echo '<table><thead>
		<tr><th data-sort="string">Gegenstand:</th>
		<th data-sort="string">Kategorie:</th>
		<th data-sort="string">Beschrieb:</th>
		<th data-sort="string">Bedingungen:</th>
		<th></th><th></th></tr></thead><tbody id="myTable">';
	$sql = 'SELECT * FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
	$res = db_anfragen($sql);
	$mitglieddaten = mysqli_fetch_assoc($res);
	$sql = 'SELECT * FROM gegenstaende WHERE mitglied_id = ' . $_SESSION['user']['id'] . ' ORDER BY id DESC';
	$res = db_anfragen($sql);
	while ($gegenstaende = mysqli_fetch_assoc($res)) {
		echo '<tr><td>' . $gegenstaende['bezeichnung'] . '</td><td>' . $gegenstaende['kategorie'] . '</td><td>' . $gegenstaende['beschrieb'] . '</td><td>' . $gegenstaende['bedingungen'] . '</td><td><a href="meine_boerse.php?id=' . $gegenstaende['id'] . '" title="bearbeiten"><img src="../../design/bearbeiten.png" height="20pt"></a></td>		<td><a href="' . $_SERVER['PHP_SELF'] . '?l=' . $gegenstaende['id'] . '"  class="entfernen_bestaetigen" title="Produkt entfernen"><img src="../../design/loeschen.png" height="20pt"></a></td></tr>';
	}
	echo '</table>';
	?> 
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
}

include 'inc/footer.php';
?>