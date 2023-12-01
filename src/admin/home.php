<?php

// Startseite des CMS' 

// HTML-Beginn einbetten
include '../inc/config.php';
include '../common/functions.php';
include 'inc/login.php';
include 'inc/header.php';

if ($_POST) {
	$sql = 'UPDATE parameter SET tuercode = "' . $_POST['tuercode'] . '" WHERE id = 1';
	$res = db_anfragen($sql);
	if ($res) {
		// create text for website
		echo '<div style="border:2px solid #e52300; padding:5px; "><b>Der Türcode wurde auf ' . $_POST['tuercode'] . ' geändert</b></div>';
	};
}

// Dieser Inhalt steht dann in der SECTION#hauptinhalt

echo '<h1>Willkommen ' . $_SESSION['user']['vorname'] . '</h1>';
$sql = 'SELECT tuercode, wlan FROM parameter WHERE id=1';
$res_parameter = db_anfragen($sql);
$parameter =  mysqli_fetch_assoc($res_parameter);

echo '<p>Türcode neu setzen <form action="" method="post"><input type="number" id="tuercode" name="tuercode" value="' . $parameter['tuercode'] . '"> <input type="submit" value="Senden"></form></p>';

// Letzte Anmeldung war am 

echo '<p>Du hast dich das letzte Mal am ';

//echo strftime('%A, %e. %B %Y um %H:%M Uhr', strtotime($_SESSION['user']['letzte_anmeldung']));
$formatter = new IntlDateFormatter('de_CH', IntlDateFormatter::LONG, IntlDateFormatter::MEDIUM);
echo $formatter->format(time()) . ' angemeldet.</p>';

$sql = 'SELECT SUM(preis) AS total FROM buchungen_mitglieder WHERE MONTH(zeit) = ' . date('n') . ' AND betreff = "Einkauf Website"';
$res = db_anfragen($sql);
$total = mysqli_fetch_assoc($res);
echo 'Einkaufsbetrag von diesem Monat: ' . $total['total'] * (-1);
echo '<br>Erlös von diesem Monat: ' . $total['total'] * (-1) * (1 - 1 / 1.14);

// Prüfen, ob sich der Benutzer das letzte Mal korrekt abgemeldet hat oder nicht

if ($_SESSION['user']['ist_angemeldet'] == 1) {

	echo '<p class="rot">Du hast dich das letzte Mal nicht korrekt abgemeldet! Bitte immer via "Abmelden" abmelden.</p>';
}

// HTML-Ende

include 'inc/footer.php';
