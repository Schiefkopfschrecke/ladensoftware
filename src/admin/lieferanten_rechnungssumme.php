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
			<th data-sort="string">Lieferant</th>
			<th data-sort="string">Prod-ID</th>
			<th data-sort="string">Produkt</th>
			<th data-sort="date">Summe</th>
		</tr>
	</thead>
	<tbody id="myTable">
		<?php
		$periodenanfang = "2021-03-12 00:00:00";
		$periodenende = "2022-03-11 00:00:00";

		echo '<p style="color:red; font-size:140%;"><b>Einkaufssumme der Lieferanten für die Periode vom ' . $periodenanfang . ' bis ' . $periodenende . ' <br>Anpassen im Quellcode in Zeile 42 und 43</b><p>';
		$sql = 'SELECT * FROM lieferanten';

		$res_lieferanten = db_anfragen($sql);
		while ($lieferanten = mysqli_fetch_assoc($res_lieferanten)) {
			$lieferantensumme = 0;
			$sql_produkte = 'SELECT * FROM produkte WHERE lieferant_id = ' . $lieferanten['id'];
			$res_produkte = db_anfragen($sql_produkte);
			while ($produkte = mysqli_fetch_assoc($res_produkte)) {
				$sql_warenkorb = 'SELECT SUM(preis) AS einkaufssumme FROM warenkorb WHERE produkt_id = ' . $produkte['id'] . ' AND  eingekauft > "' . $periodenanfang . '" AND  eingekauft < "' . $periodenende . '"';
				$res_warenkorb = db_anfragen($sql_warenkorb);
				$einkaufssumme = mysqli_fetch_assoc($res_warenkorb);
				$lieferantensumme = $lieferantensumme + $einkaufssumme['einkaufssumme'];
				/*	echo '<tr>';
					echo '<td>' . $lieferanten['id'] . '</td>';
					echo '<td>' . $lieferanten['firmenname'] . '</td>';
					echo '<td>' . $produkte['id'] . '</td>';
					echo '<td>' . $produkte['produktname'] . '</td>';
					echo '<td>' . $einkaufssumme['einkaufssumme'] . '</td></tr>'; */
			}
			echo '<tr style="color:red; font-size:120%; font-weight: bold;">';
			echo '<td>' . $lieferanten['id'] . '</td>';
			echo '<td>' . $lieferanten['firmenname'] . '</td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td align="right">' . number_format($lieferantensumme, 2, '.', '') . '</td></tr>';
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
include 'inc/footer.php';
?>