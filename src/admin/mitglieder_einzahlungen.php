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

// Dieser Inhalt steht dann in der SECTION#hauptinhalt
?>
<style type="text/css">
	th[data-sort] {
		cursor: pointer;
	}

	.disabled {
		opacity: 0.5;
	}
</style>
<table>
	<tr>
		<td width="300pt"><input id='myInput' onkeyup='searchTable()' type='text' placeholder="Suche Mitglied" autofocus></p>
		</td>
		<td width="300pt" align="right"><a href="buchungen_kontrollieren.php">Buchungen kontrollieren</a></td>
	</tr>
</table>
<p>
<table>
	<thead>
		<tr>
			<th data-sort="int">ID</th>
			<th data-sort="string">Vorname</th>
			<th data-sort="string">Name</th>
			<th data-sort="string">Einzahlungen</th>
			<th data-sort="date">Datum</th>
			<th data-sort="string">Total</th>
			<th>bearbeiten</th>
		</tr>
	</thead>
	<tbody id="myTable">
		<?php $sql = 'SELECT * FROM mitglieder';

		$res = db_anfragen($sql);
		$summe_einzahlungen = 0;

		while ($mitglieder = mysqli_fetch_assoc($res)) {
			$sql = 'SELECT * FROM buchungen_mitglieder WHERE mitglied_id = ' . $mitglieder['id'] . ' AND betreff = "Einzahlung(+)"';
			$res_einzahlung = db_anfragen($sql);
			while ($einzahlungen = mysqli_fetch_assoc($res_einzahlung)) {;

				echo '<tr>';
				echo '<td>' . $mitglieder['id'] . '</td>';
				echo '<td>' . $mitglieder['vorname'] . '</td>';
				echo '<td class="name">' . $mitglieder['name'] . '</td>';
				echo '<td>' . $einzahlungen['preis'] . '</td>';
				$summe_einzahlungen = $summe_einzahlungen + $einzahlungen['preis'];
				echo '<td>' . date('d. m. Y', strtotime($einzahlungen['zeit'])) . '</td>';
				echo '<td></td>';
				echo '<td><a href="mitglied_bearbeiten.php?mitglied_id=' . $mitglieder['id'] . '" title="Mitglied bearbeiten">bearbeiten</a></td></tr>';
			}
			echo '<tr>';
			echo '<td><b>Total</b></td>';
			echo '<td>' . $mitglieder['vorname'] . '</td>';
			echo '<td class="name">' . $mitglieder['name'] . '</td>';
			echo '<td><b>' . $summe_einzahlungen . '</b></td>';
			echo '<td><b>Ohne ATS:</b></td>';
			$summe_ohne_ats = $summe_einzahlungen - $mitglieder['anteilscheine'] * 150;
			echo '<td><b>' . $summe_ohne_ats . '</b></td></tr>';
			$summe_einzahlungen = 0;
		}

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

$sql = 'SELECT email FROM mitglieder WHERE status = "berechtigt"';
$res = db_anfragen($sql);
while ($mitglieder = mysqli_fetch_assoc($res)) {
	$alle_adressen = $alle_adressen . ', ' . $mitglieder['email'];
}
echo '<h2>Berechtigte Mitglieder</h2><p>' . $alle_adressen . '</p>';

include 'inc/footer.php';

?>