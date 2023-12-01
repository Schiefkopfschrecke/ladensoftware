<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
//Load Composer's autoloader
require '../vendor/autoload.php';

// HTML-Beginn einbetten
include '../inc/config.php';
include 'inc/login.php';
include '../common/functions.php';
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
<p><input id='myInput' onkeyup='searchTable()' type='text' placeholder="Volltextsuche" autofocus></p>
<table>
	<thead>
		<tr>
			<th data-sort="string">Datum</th>
			<th data-sort="string">Vorname</th>
			<th data-sort="string">Name</th>
			<th data-sort="string">Betrag</th>
		</tr>
	</thead>
	<tbody id="myTable">
		<?php 
		echo '<h2>Monat auswählen: ';
		$diesermonat_monat = date("M");
		$diesermonat_m = date("m");
		echo '<a href="buchungen_kontrollieren.php?pruefmonat=' . $diesermonat_m . '">' . $diesermonat_monat . '</a>';
		$diesermonat_monat = date("M", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
		$diesermonat_m = date("m", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
		echo '&nbsp;¦&nbsp;<a href="buchungen_kontrollieren.php?pruefmonat=' . $diesermonat_m . '">' . $diesermonat_monat . '</a>';
		$diesermonat_monat = date("M", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month"));
		$diesermonat_m = date("m", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month"));
		echo '&nbsp;¦&nbsp;<a href="buchungen_kontrollieren.php?pruefmonat=' . $diesermonat_m . '">' . $diesermonat_monat . '</a>';
		$diesermonat_monat = date("M", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-3 month"));
		$diesermonat_m = date("m", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-3 month"));
		echo '&nbsp;¦&nbsp;<a href="buchungen_kontrollieren.php?pruefmonat=' . $diesermonat_m . '">' . $diesermonat_monat . '</a>';
		echo '</h2>';
		$anz_einzahlungen = 0;
		$summe_einzahlungen = 0;

		$sql = 'SELECT * FROM buchungen_mitglieder WHERE betreff= "Einzahlung(+)" ORDER BY id DESC LIMIT 120';

		$res = db_anfragen($sql);
		while ($einzahlungen = mysqli_fetch_assoc($res)) {
			$sql = 'SELECT name, vorname FROM mitglieder WHERE id = ' . $einzahlungen['mitglied_id'];
			$res_mitglied = db_anfragen($sql);
			$mitglied = mysqli_fetch_assoc($res_mitglied);
			$buchungsmonat = date("m", strtotime($einzahlungen['zeit']));
			if ($buchungsmonat == $_GET['pruefmonat']) {
				echo '<tr>';
				echo '<td>' . date("Y.m.d", strtotime($einzahlungen['zeit'])) . '</td>';
				echo '<td>' . $mitglied['vorname'] . '</td>';
				echo '<td class="name">' . $mitglied['name'] . '</td>';
				echo '<td>' . $einzahlungen['preis'] . '</td></tr>';
				$anz_einzahlungen = $anz_einzahlungen + 1;
				$summe_einzahlungen = $summe_einzahlungen + $einzahlungen['preis'];
			}
		}
		?>

	</tbody>
</table>

<?php 
echo '<h2>Anzahl Einzahlungen: ' . $anz_einzahlungen . '</h2>';
echo '<h2>Summe der Einzahlungen: ' . $summe_einzahlungen . '</h2>';
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