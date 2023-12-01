<?php

// Session verwenden: eigenen Namen angeben

session_name('meine-einkaufs-session');

// Session starten

session_start();

// Session-ID neu vergeben

session_regenerate_id();
$_SESSION['last_visit'] = time();

// Das Formular wurde verschickt

// Prüfen, ob es diesen Benutzer gibt
// XX Prüfen ob er die Berechtigung hat oder nur dann Einkaufsformular und Türcode anzeigen lassen
$benutzername = filter_input(INPUT_POST, 'benutzername');	// vgl. http://php.net/manual/en/filter.filters.validate.php

$pw = filter_input(INPUT_POST, 'passwort');	// vgl. http://php.net/manual/en/filter.filters.sanitize.php

// Korrekte Mailadresse vorhanden?

if ($benutzername) {

	// Ja, jetzt den Passwort-Hash vorbereiten

	$pw = sha1($pw);

	$pw = sha1($pw . 'transformator');

	// DB fragen, ob es diesen User gibt

	include 'inc/functions.php';

	$res = db_anfragen('SELECT * FROM mitglieder WHERE benutzername = "' . $benutzername . '" AND passwort = "' . $pw . '" LIMIT 1');
	// Haben wir einen solchen Benutzer gefunden?

	if (mysqli_num_rows($res)) {

		// Ja, es gibt einen, diesen speichern wir in der Session

		$_SESSION['user'] = mysqli_fetch_assoc($res);

		// Da sich der Benutzer soeben korrekt angemeldet hat, speichern wir dies in der DB

		$res = db_anfragen('UPDATE mitglieder SET ist_angemeldet = 1, letzte_anmeldung = NOW() WHERE id = ' . $_SESSION['user']['id']);

		// Zum Home des CMS' weiterleiten

		header('Location:einkaufen.php');
	}
}

?>

<?php
include 'inc/config.php';
include 'inc/header.php';
?>

<form method="post">

	<p>
		<label for="benutzername">Benutzername</label>
		<input type="text" name="benutzername" id="benutzername" maxlength="200" autocomplete="off" autofocus />
	</p>

	<p>
		<label for="passwort">Passwort</label>
		<input type="password" name="passwort" id="passwort" maxlength="20" autocomplete="off" />
	</p>

	<p>
		<input type="submit" value="Login" />
	</p>

</form>

<p><a href="passwort_vergessen.php">Passwort & Benutzername vergessen?</a></p>

<?php
// XXLogin vergessen
// HTML-Ende
include 'inc/footer.php';
?>