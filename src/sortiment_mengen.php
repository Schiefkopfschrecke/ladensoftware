<?php
error_reporting(0);

include  'inc/functions.php';
include  'inc/config.php';
include  'inc/header.php';
?>

<div id="Produkteliste">
	<p>Wir haben voraussichtlich folgende Produkte (Preise) im Sortiment (alle Angaben ohne Gewähr): </p>
	<!--<p>XXProduktename, Produzent mit Link auf Beschrieb, Preis / Einheit, Status/vorhandene Menge</p>-->
	<?		// Marge abrufen
	$sql = 'SELECT marge FROM parameter WHERE id=1';
	$res_marge = db_anfragen($sql);
	$marge =  mysqli_fetch_assoc($res_marge);
	$marge = $marge['marge'];
	$sql = 'SELECT id, firmenname FROM lieferanten';
	echo $sql;
	$res_lieferanten = db_anfragen($sql);

	while ($alle_lieferanten = mysqli_fetch_assoc($res_lieferanten)) {
		echo  '<h2>' . $alle_lieferanten['firmenname'] . '</h2><table><tr><td>ID:</td><td>Produkt:</td><td>Preis:</td><td>Einkaufsmenge:</td><td>Verkaufte Menge</td><td>Rest:</td>';
		$sql = 'SELECT * FROM produkte WHERE lieferant_id = "' . $alle_lieferanten['id'] . '"';
		$res = db_anfragen($sql);
		while ($produkte = mysqli_fetch_assoc($res)) {
			$sql = 'SELECT SUM(menge) AS total_einkaeufe FROM warenkorb WHERE produkt_id = ' . $produkte['id'];
			$res_total_einkaeufe = db_anfragen($sql);
			$total =  mysqli_fetch_assoc($res_total_einkaeufe);

			$diverenz_produkt = number_format($produkte['menge_letzte_bestellung'], 0, '', '') - $total['total_einkaeufe'];
			if ($total['total_einkaeufe'] / $produkte['menge_letzte_bestellung'] > 0.9) {
				$formatvariable = ' style = "color: red;" ';
			} else {
				$formatvariable = '';
			}
			if ($produkte['einheit'] == "g") {
				$preis = number_format($produkte['vp_pro_einheit'] * $marge * 100, 2, '.', '');
				echo '<tr><td>' . $produkte['id'] . '</td><td>' . $produkte['produktname'] . '</td><td>' . $preis . ' CHF/100 g</td><td>' . number_format($produkte['menge_letzte_bestellung'] / 1000, 2, '.', '') . ' kg</td><td>' . number_format($total['total_einkaeufe'] / 1000, 2, '.', '') . ' kg</td><td' . $formatvariable . '>' . number_format($diverenz_produkt / 1000, 2, '.', '') . ' kg</td></tr>';
			} else {
				$preis = number_format($produkte['vp_pro_einheit'] * $marge, 2, '.', '');
				echo '<tr><td>' . $produkte['id'] . '</td><td>' . $produkte['produktname'] . '</td><td>' . $preis . ' CHF/' . $produkte['einheit'] . '</td><td>' . number_format($produkte['menge_letzte_bestellung'], 0, '.', '') . '</td><td>' . number_format($total['total_einkaeufe'], 0, '.', '') . '</td><td>' . number_format($diverenz_produkt, 0, '.', '') . '</td></tr>';
			}
		}
		echo '</table>';
	}

	?>
	<!--XX Evtl. mit Rückmeldungsmöglichkeit zu den Produkten
	<p>Wenn du weitere Produkte wünschts und/oder eine*n sympatisch*e Produzenten*in kennst, melde dich bei XXX</p>-->
</div>

<?php
include  'inc/footer.php';
?>