<?php
include  'inc/functions.php';
include  'inc/config.php';
include  'inc/header.php';
?>

<div id="produkteliste">
	<p>Wir haben voraussichtlich folgende Produkte (Preise) im Sortiment (alle Angaben ohne Gewähr): </p>
	<p><input id='myInput' onkeyup='searchTable()' type='text' placeholder="Suche Produkt & Produzent" autofocus></p>

	<!--<p>XXProduktename, Produzent mit Link auf Beschrieb, Preis / Einheit, Status/vorhandene Menge</p>-->
	<?		// Marge abrufen
	$sql = 'SELECT marge FROM parameter WHERE id=1';
	$res_marge = db_anfragen($sql);
	$marge =  mysqli_fetch_assoc($res_marge);
	$marge = $marge['marge'];

	echo  '<table><thead><tr><th>ID</th><th>Produkt:</th><th>Preis:</th><th>Produzent:<br><i>Lieferant:</i></th><th>Label:</th></tr></thead><tbody id="myTable">';

	$sql = 'SELECT * FROM produkte WHERE kategorie != "versteckt"';
	$res = db_anfragen($sql);

	while ($produkte = mysqli_fetch_assoc($res)) {
		$sql = 'SELECT * FROM produzenten WHERE id = ' . $produkte['produzent_id'];
		$res_produzenten = db_anfragen($sql);
		$produzenteninfo = mysqli_fetch_assoc($res_produzenten);
		$sql = 'SELECT * FROM lieferanten WHERE id = ' . $produkte['lieferant_id'];
		$res_lieferanten = db_anfragen($sql);
		$lieferanteninfo = mysqli_fetch_assoc($res_lieferanten);
		if ($produkte['einheit'] == "g") {
			$preis = number_format($produkte['vp_pro_einheit'] * $produkte['rabatt'] * $marge * 100, 2, '.', '');
			echo '<tr><td>' . $produkte['id'] . '</td><td>' . $produkte['produktname'] . '</td><td>' . $preis . ' CHF/100 g';
			if ($produkte['rabatt'] < 1) {
				echo '<br>auf ' . $produkte['rabatt'] * 100 . '% reduziert';
			}
			echo '</td><td>';
			if ($produzenteninfo['url']) {
				echo '<a href="' . $produzenteninfo['url'] . '" target="_blank" title="Website des Produzenten">';
			}
			echo  $produzenteninfo['firmenname'] . '</a><br><i>';
			if ($lieferanteninfo['url']) {
				echo '<a href="' . $lieferanteninfo['url'] . '" target="_blank" title="Website des Lieferanten">';
			}
			echo  $lieferanteninfo['firmenname'] . '</a></td><td>' . $produkte['label'] . '</td></tr>';
		} else {
			$preis = number_format($produkte['vp_pro_einheit'] * $produkte['rabatt'] * $marge, 2, '.', '');
			echo '<tr><td>' . $produkte['id'] . '</td><td>' . $produkte['produktname'] . '</td><td>' . $preis . ' CHF/' . $produkte['einheit'];
			if ($produkte['rabatt'] < 1) {
				echo '<br>auf ' . $produkte['rabatt'] * 100 . '% reduziert';
			}
			echo '</td><td>';
			if ($produzenteninfo['url']) {
				echo '<a href="' . $produzenteninfo['url'] . '" target="_blank" title="Website des Lieferanten">';
			}
			echo  $produzenteninfo['firmenname'] . '</a></td><td>' . $produkte['label'] . '</td></tr>';
		}
	}
	echo '</tbody></table>';

	?>
	<!--XX Evtl. mit Rückmeldungsmöglichkeit zu den Produkten
	<p>Wenn du weitere Produkte wünschts und/oder eine*n sympatisch*e Produzenten*in kennst, melde dich bei XXX</p>-->

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
</div>

<?php
include  'inc/footer.php';
?>