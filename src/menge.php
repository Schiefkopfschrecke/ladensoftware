<?php
error_reporting(E_ALL);

include  'inc/functions.php';
include  'inc/login.php';
include  'inc/config.php';
include  'inc/header.php';

if ($_POST) {
	$mitglied_id = filter_input(INPUT_POST, 'mitglied_id', FILTER_VALIDATE_INT);

	// Empfangene Daten säubern

	$produkt_id = filter_input(INPUT_POST, 'produkt_id', FILTER_SANITIZE_NUMBER_INT);

	// Daten des Produktes aufrufen
	$sql = 'SELECT vp_pro_einheit, rabatt, produktname, lieferant_id, produzent_id, einheit, label FROM produkte WHERE id = ' . $produkt_id;
	$res = db_anfragen($sql);
	// Fehler ausgeben, falls Produkt nicht vorhanden ist
	if (mysqli_num_rows($res) == 0) {
		echo '<div style="border:2px solid #e52300; padding:5px; "><p><b>Das Produkt mit der Nummer ' . $produkt_id . ' ist nicht in der Datenbank vorhanden</b></p><a href="einkaufen.php"><input type="button" value="weiter einkaufen" action="einkaufen.php"></a></div>';
		// wenn Produkt vorhanden ist, Daten von Produzenten und Lieferanten abfragen
	} else {
		$produktinfo = mysqli_fetch_assoc($res);
		$sql = 'SELECT firmenname, url, produzenten_beschrieb FROM produzenten WHERE id = ' . $produktinfo['produzent_id'];
		$res_produzent = db_anfragen($sql);
		$produzentinfo = mysqli_fetch_assoc($res_produzent);
		$sql = 'SELECT firmenname, url, lieferanten_beschrieb FROM lieferanten WHERE id = ' . $produktinfo['lieferant_id'];
		$res_lieferant = db_anfragen($sql);
		$lieferantinfo = mysqli_fetch_assoc($res_lieferant);

		// Formular für Menge
		echo '<div id="kaufen"><form action="einkaufen.php" method="post" enctype="multipart/form-data">

				<input type="hidden" name="mitglied_id" id="mitglied_id" hidden value="';
		echo $mitglied_id;
		echo '">
				<input type="hidden" name="produkt_id" id="produkt_id" hidden value="';
		echo $produkt_id;
		echo '">';
		echo $produkt_id . ' ' . $produktinfo['produktname'];
		if ($produktinfo['rabatt'] < 1) {
			echo ' (auf ' . $produktinfo['rabatt'] * 100 . '% reduziert)';
		}

		echo ' <br><input type="number" name="menge" id="menge" required placeholder="Menge" autocomplete="off" autofocus> <b>in ';
		echo $produktinfo['einheit'];
		echo '</b><br> 
				
				<input type="submit" class="button"';
		if ($_SESSION['user']['id'] == 14) {
			echo ' style="background: #FF00CE" ';
		}
		echo ' value="In den Warenkorb">

        </p>

    <p><br><a href="einkaufen.php"><input type="button" value="upsydasy, falsches Produkt!" action="einkaufen.php"></a></p>

    </form></div>';
		$sql = 'SELECT marge FROM parameter WHERE id=1';
		$res_marge = db_anfragen($sql);
		$marge =  mysqli_fetch_assoc($res_marge);
		$marge = $marge['marge'];

		// Tabelle mit Infos zum Produkt ausgeben
		echo '<div id="produkteinfo"><b>';
		echo $produktinfo['produktname'];
		echo '</b><table><tr><td>Preis:</td><td>';

		if ($produktinfo['einheit'] == "g") {
			$preis = number_format($produktinfo['vp_pro_einheit'] * $produktinfo['rabatt'] * $marge * 100, 2, '.', '');
			echo $preis . ' CHF/100 g</td></tr>';
		} else {
			$preis = number_format($produktinfo['vp_pro_einheit'] * $produktinfo['rabatt'] * $marge, 2, '.', '');
			echo $preis . ' CHF/' . $produktinfo['einheit'] . '</td></tr>';
		}
		echo '<tr><td>Label:</td><td>' . $produktinfo['label'] . '</td></tr>';
		if ($produktinfo['produzent_id'] > 0) {
			echo '<tr><td>Produzent:</td><td>';
			if ($produzentinfo['url']) {
				echo '<a href="' . $produzentinfo['url'] . '" target="_blank" title="Website ' . $produzentinfo['firmenname'] . '">';
			}
			echo $produzentinfo['firmenname'] . '</a><br>' . $produzentinfo['produzenten_beschrieb'];
		}
		echo '</td></tr>';
		echo '<tr><td>Lieferant:</td><td>';
		if ($lieferantinfo['url']) {
			echo '<a href="' . $lieferantinfo['url'] . '" target="_blank" title="Website ' . $lieferantinfo['firmenname'] . '">';
		}
		echo $lieferantinfo['firmenname'] . '</a><br>' . $lieferantinfo['lieferanten_beschrieb'];
	}
	echo '</td></tr>';
	echo '</table></div>';
}

include  'inc/footer.php';
