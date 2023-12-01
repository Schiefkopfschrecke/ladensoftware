<?php

error_reporting(E_ALL);

// include functions, configurations and header
include 'inc/functions.php';
include 'inc/login.php';
include 'inc/config.php';
include 'inc/header.php';

$sql = 'SELECT * FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
$res = db_anfragen($sql);
$mitglieddaten = mysqli_fetch_assoc($res);
echo '<div id="letzte_einkaeufe"><h1>Deine Einkäufe</h1><p>Fehler kannst du korrigieren, indem du das Produkt mit einer negativen Mengenangabe einkaufst.</p>';
$sql = 'SELECT zeit, preis, betreff FROM buchungen_mitglieder WHERE mitglied_id = ' . $_SESSION['user']['id'] . ' ORDER BY zeit DESC';
$res = db_anfragen($sql);
while ($einkaufsdaten = mysqli_fetch_assoc($res)) {
	echo '<h3>' . $einkaufsdaten['betreff'] . ': ' . date("d.m.Y - H:i", strtotime($einkaufsdaten['zeit'])) . ' für CHF ' . number_format($einkaufsdaten['preis'], 2, ".", "'") . '</h3>';
	if ($einkaufsdaten['betreff'] = 'Einkauf Webseite') {
		echo '<table>';
		$sql = 'SELECT * FROM warenkorb WHERE eingekauft = "' . $einkaufsdaten['zeit'] . '" AND mitglied_id = ' . $_SESSION['user']['id'];
		$res_warenkorb = db_anfragen($sql);
		while ($warenkorbdaten = mysqli_fetch_assoc($res_warenkorb)) {
			$sql = 'SELECT produktname, einheit FROM produkte WHERE id = ' . $warenkorbdaten['produkt_id'];
			$res_produkt = db_anfragen($sql);
			$produktdaten = mysqli_fetch_assoc($res_produkt);
			echo '<tr><td>' . $warenkorbdaten['produkt_id'] . '</td><td>' . $produktdaten['produktname'] . '</td><td>' . number_format($warenkorbdaten['menge'], 0, ".", "'") . ' ' . $produktdaten['einheit'] . '</td><td> CHF ' . $warenkorbdaten['preis'] . '</td></tr>';
		}
		echo '</table>';
	}
}

echo '</div>';

include 'inc/footer.php';
