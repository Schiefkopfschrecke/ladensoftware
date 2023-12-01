<?php

// HTML-Beginn einbetten
include '../inc/config.php';
include 'inc/functions.php';
include 'inc/login.php';
include 'inc/header.php';

$zu_loeschendes_produkt = filter_input(INPUT_GET, 'l', FILTER_VALIDATE_INT);
// Wenn diese ID grösser als Null ist, möchten wir etwas löschen

if ($zu_loeschendes_produkt > 0) {

	// Jetzt den Datensatz löschen

	$res = db_anfragen('DELETE FROM produkte WHERE id = ' . $zu_loeschendes_produkt);

	// Wieder zur Übersicht ohne Variablen in der Adresszeile leiten

	header('Location:' . $_SERVER['PHP_SELF']);
}

if ($_POST) {

	// Verschickte ID prüfen

	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

	// Empfangene Daten säubern 

	$produktname = filter_input(INPUT_POST, 'produktname', FILTER_SANITIZE_STRING);

	$lieferant_id = filter_input(INPUT_POST, 'lieferant_id', FILTER_VALIDATE_INT);
	$produzent_id = filter_input(INPUT_POST, 'produzent_id', FILTER_VALIDATE_INT);
	$einheit = filter_input(INPUT_POST, 'einheit', FILTER_SANITIZE_STRING);
	$ekp_pro_einheit = filter_input(INPUT_POST, 'ekp_pro_einheit', FILTER_SANITIZE_STRING);
	$vp_pro_einheit = filter_input(INPUT_POST, 'vp_pro_einheit', FILTER_SANITIZE_STRING);
	$mwst_satz = filter_input(INPUT_POST, 'mwst_satz', FILTER_SANITIZE_STRING);
	$rabatt = filter_input(INPUT_POST, 'rabatt', FILTER_SANITIZE_STRING);
	$kategorie = filter_input(INPUT_POST, 'kategorie', FILTER_SANITIZE_STRING);
	$label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);

	$bemerkungen = filter_input(INPUT_POST, 'bemerkungen', FILTER_SANITIZE_STRING);
	$preis_id = filter_input(INPUT_GET, 'preis_id', FILTER_VALIDATE_INT);

	// Skript für Preisanpassung in der Haupttabelle	
	if ($preis_id > 0) {
		$sql = 'UPDATE produkte
					SET ekp_pro_einheit = ' . $_POST['ekp_pro_einheit'] . ',
						vp_pro_einheit = ' . $_POST['vp_pro_einheit'] . '
					WHERE id = ' . $preis_id;

		$res = db_anfragen($sql);
		//		header( 'Location:' . $_SERVER['PHP_SELF']. '?preis_id=' . $preis_id . '&ekp_pro_einheit=' . $ekp_pro_einheit );
	}

	// Falls ein bestehender Produkte-Datensatz aktualisiert werden muss

	if ($id > 0) {

		// Update

		$sql = 'UPDATE produkte 
					SET produktname = "' . $produktname . '", 
						lieferant_id = "' . $lieferant_id . '", 
						produzent_id = "' . $produzent_id . '", 
						einheit = "' . $einheit . '", 
						ekp_pro_einheit = "' . $ekp_pro_einheit . '", 
						vp_pro_einheit = "' . $vp_pro_einheit . '", 
						rabatt = "' . $rabatt . '", 
						mwst_satz = "' . $mwst_satz . '", 
						kategorie = "' . $kategorie . '", 
						label = "' . $label . '", 
						bemerkungen = "' . $bemerkungen . '"
					WHERE id = ' . $id;

		db_anfragen($sql);
	}
	// Einfügen eines neuen Produktes in die Datenbank
	else if ($id === 0) {

		// Insert-Statement ausführen

		$sql = 'INSERT INTO 

						produkte ( produktname, lieferant_id, produzent_id, einheit, ekp_pro_einheit, vp_pro_einheit, rabatt, mwst_satz, kategorie, label, bemerkungen )

					VALUES (
						"' . $produktname . '", 
						"' . $lieferant_id . '", 
						"' . $produzent_id . '", 
						"' . $einheit . '", 
						"' . $ekp_pro_einheit . '", 
						"' . $vp_pro_einheit . '", 
						"' . $rabatt . '", 
						"' . $mwst_satz . '", 
						"' . $kategorie . '", 
						"' . $label . '", 
						"' . $bemerkungen . '"
					)';

		db_anfragen($sql);
	}

	// Auf die Liste weiterleiten

	header('Location:' . $_SERVER['PHP_SELF'] . '?preis_id=' . $preis_id);
}

// C: HTML-Beginn einbetten

//include 'inc/header.php';

// Dieser Inhalt steht dann in der SECTION#hauptinhalt

echo '<h1>Produkte verwalten</h1>';

// Rückmeldung wenn Preis über Haupttabelle angepasst wurde
if ($_GET['preis_id']) {
	$sql = 'SELECT produktname, ekp_pro_einheit, vp_pro_einheit FROM produkte WHERE id = ' . $_GET['preis_id'];
	$res = db_anfragen($sql);
	$produktinfo = mysqli_fetch_assoc($res);
	echo '<div style="border:2px solid #e52300; padding:5px; "><b>Preisänderung beim Produkt ' . $produktinfo['produktname'] . ' (Nr. ' . $_GET['preis_id'] . ') geändert: Einkaufspreis = CHF ' . $produktinfo['ekp_pro_einheit'] . ', Verkaufspreis = CHF ' . $produktinfo['vp_pro_einheit'] . ' geändert</b></div>';
}

// D: ID filtern. In der Adresszeile steht etwas wie "beitraege.php?id=123"

//	  Die ID ist danach entweder eine Ganzzahl oder FALSE

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Wir gehen davon aus, dass kein Formular gezeigt werden muss

$edit = false;

// E: ID prüfen und $edit füllen

// Falls die ID eine Ganzzahl > 0 ist, entsprechender Datensatz holen

if ($id > 0) {

	$res = db_anfragen('SELECT * FROM produkte WHERE id = ' . $id);

	// Diesen Datensatz als assoziativen Array bereitstellen

	$edit = mysqli_fetch_assoc($res);
}

//#####################

// Edit-angaen bearbeiten

// Sonst, falls die ID genau der Zahl 0 entspricht, soll ein neuer Datensatz vorbereitet werden

else if ($id === 0) {

	// Neuer leerer assoziativer Array vorbereiten

	$edit = array(

		'produktname' => '',
		'lieferant_id' => '',
		'produzent_id' => '',
		'einheit' => '',
		'ekp_pro_einheit' => '',
		'vp_pro_einheit' => '',
		'rabatt' => '',
		'mwst_satz' => '',
		'kategorie' => '',
		'label' => '',
		'bemerkungen' => ''
	);
}

// F: Ist ein $edit vorhanden? JA: Formular zeigen

if ($edit) {

	// Formular zur Produkterfassung

?>

	<form method="post" enctype="multipart/form-data">

		<input type="hidden" name="id" value="<?php echo $id; ?>" />

		<table>
			<tr>
				<td>Produktname:*</td>
				<td><input type="text" name="produktname" id="produktname" required placeholder="Produktname*" value="<?php echo htmlspecialchars(stripslashes($edit['produktname'])); ?>"> </td>
			</tr>
			<td>Lieferant:</td>
			<td><?php ?><select id="lieferant_id" name="lieferant_id">
					<option value="keine Angabe">keine Angabe</option>
					<?php $sql = 'SELECT id, firmenname FROM lieferanten ORDER BY firmenname';
					$res = db_anfragen($sql);
					while ($lieferanten = mysqli_fetch_assoc($res)) {
						echo '<option value="' . $lieferanten['id'] . '" ';
						if ($lieferanten['id'] == htmlspecialchars(stripslashes($edit['lieferant_id']))) {
							echo 'selected';
						}
						echo '>' . $lieferanten['firmenname']  . '</option>';
					}
					?>
				</select> </td>
			</tr>
			<td>Produzent:</td>
			<td><?php ?><select id="produzent_id" name="produzent_id">
					<option value="keine Angabe">keine Angabe</option>
					<?php $sql = 'SELECT id, firmenname FROM produzenten ORDER BY firmenname';
					$res = db_anfragen($sql);
					while ($produzenten = mysqli_fetch_assoc($res)) {
						echo '<option value="' . $produzenten['id'] . '" ';
						if ($produzenten['id'] == htmlspecialchars(stripslashes($edit['produzent_id']))) {
							echo 'selected';
						}
						echo '>' . $produzenten['firmenname']  . '</option>';
					}
					?>
				</select> </td>
			</tr>
			<tr>
				<td>Einheit:*</td>
				<td><input type="text" name="einheit" id="einheit" required placeholder="Einheit*" value="<?php echo htmlspecialchars(stripslashes($edit['einheit'])); ?>"> </td>
			</tr>
			<tr>
				<td>EKP/Einheit:*</td>
				<td><input type="text" name="ekp_pro_einheit" id="ekp_pro_einheit" required placeholder="EKP/Einheit*" value="<?php echo htmlspecialchars(stripslashes($edit['ekp_pro_einheit'])); ?>"> </td>
			</tr>
			<tr>
				<td>VP/Einheit:*</td>
				<td><input type="text" name="vp_pro_einheit" id="vp_pro_einheit" required placeholder="VP/Einheit*" value="<?php echo htmlspecialchars(stripslashes($edit['vp_pro_einheit'])); ?>"> </td>
			</tr>
			<tr>
				<td>Preisanpassung:*</td>
				<td><select id="rabatt" name="rabatt">
						<option value="1" <?php if ($edit['rabatt'] == 1) {
												echo 'selected';
											} ?>>100%</option>
						<option value="0.88" <?php if ($edit['rabatt'] == 0.88) {
													echo 'selected';
												} ?>>88% (EKP)</option>
						<option value="0.5" <?php if ($edit['rabatt'] == 0.5) {
												echo 'selected';
											} ?>>50%</option>
					</select> </td>
			</tr>
			<tr>
				<td>MwSt-Satz:*</td>
				<td><input type="text" name="mwst_satz" id="mwst_satz" required placeholder="MwSt-Satz*" value="<?php echo htmlspecialchars(stripslashes($edit['mwst_satz'])); ?>"> </td>
			</tr>
			<tr>
				<td>Kategorie:*</td>
				<td><select id="kategorie" name="kategorie"><?php foreach ($config['produktkategorien'] as $eine_kategorie) {
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
				<td>Label:</td>
				<td><input type="text" name="label" id="label" required placeholder="Label" value="<?php echo htmlspecialchars(stripslashes($edit['label'])); ?>"> </td>
			</tr>
			<tr>
				<td>Bemerkungen:</td>
				<td><input type="text" name="bemerkungen" id="bemerkungen" placeholder="bemerkungen" value="<?php echo htmlspecialchars(stripslashes($edit['bemerkungen'])); ?>"> </td>
			</tr>
		</table>

		<p>

			<input type="submit" value="Speichern" />

		</p>

	</form>
	</div>

<?php

}

// Nein: Alle Datensätze auflisten, neuste Einträge zuoberst

else {

	// Link, um einen neuen Datensatz zu generieren

	echo '<p><a href="' . $_SERVER['PHP_SELF'] . '?id=0">Erfassen</a></p>';
	// Feld für Tabellensuche
	echo ' <p><input id="myInput" onkeyup="searchTable()" type="text" placeholder="Suche Produkt" autofocus></p>';

	$res = db_anfragen('SELECT * FROM produkte ORDER BY id');

	echo '<table><thead>';

	// Tabellentitel
	echo '	<tr>
		<th data-sort="int">ID:</th>
		<th data-sort="string">Produkt:</th>
		<th data-sort="string">Kategorie:</th>
		<th data-sort="string">Lieferant:</th>
		<th data-sort="string">Produzent:</th>
		<th data-sort="string">Einheit:</th>
		<th data-sort="int">VP/Einheit:</th>
		<th data-sort="int">Rabatt:</th>
		<th data-sort="int">MwSt-Satz:</th>
		<th data-sort="int">Bem:</th>
		<th></th>
		<th></th>
	</tr></thead><tbody id="myTable">';

	// Solange es Datensätze hat
	while ($datensatz = mysqli_fetch_assoc($res)) {

		$sql = 'SELECT * FROM produkte';

		$res = db_anfragen($sql);

		while ($produkte = mysqli_fetch_assoc($res)) {
			$sql = 'SELECT firmenname FROM lieferanten WHERE id = ' . $produkte['lieferant_id'];
			$res_lieferant = db_anfragen($sql);
			$lieferant = mysqli_fetch_assoc($res_lieferant);

			$sql = 'SELECT firmenname FROM produzenten WHERE id = ' . $produkte['produzent_id'];
			$res_produzent = db_anfragen($sql);
			$produzent = mysqli_fetch_assoc($res_produzent);

			echo '<tr>';
			echo '<td>' . $produkte['id'] . '</td>';
			echo '<td>' . $produkte['produktname'] . '</td>';
			echo '<td>' . $produkte['kategorie'] . '</td>';
			echo '<td>' . $produkte['lieferant_id'] . ' ' . $lieferant['firmenname'] . '</td>';
			echo '<td>' . $produkte['produzent_id'] . ' ' . $produzent['firmenname'] . '</td>';
			echo '<td>' . $produkte['einheit'] . '</td>';
			echo '<td>'; 
?>
			<form action="produkte_verwalten.php?preis_id=<?php echo $produkte['id']; ?>" method="post">
				<input type="hidden" name="produkt_id" id="produkt_id" value="<?php echo $produkte['id'] ?>">
				EKP: <input type="text" name="ekp_pro_einheit" id="ekp_pro_einheit" value="<?php echo $produkte['ekp_pro_einheit'] ?>"><br>VP: <input type="text" name="vp_pro_einheit" id="vp_pro_einheit" value="<?php echo $produkte['vp_pro_einheit'] ?>">
				<input type="submit" class="button" value="Speichern">
			</form>
<?php 
			echo '</td>';
			echo '<td>' . $produkte['rabatt'] . '</td>';
			echo '<td>' . $produkte['mwst_satz'] . '</td>';
			echo '<td>' . substr($produkte['bemerkungen'], 0, 100) . '...</td>';
			echo '<td><a href="produkte_verwalten.php?id=' . $produkte['id'] . '" title="Produkt bearbeiten"><img src="../design/bearbeiten.png" title="Lieferant bearbeiten" height="20pt"></a></td>';
			echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?l=' . $produkte['id'] . '"  class="entfernen_bestaetigen" title="Produkt entfernen"><img src="../../design/loeschen.png" height="20pt"></a></td>';
			echo '</tr>';
		}
	}
	echo '</tbody></table>';
} // Ende F
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

<?php 
include 'inc/footer.php';
?>