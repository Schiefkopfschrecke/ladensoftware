<?php

require_once '../config/Secrets.php';

function mail_senden($an, $von, $betreff, $mail_inhalt)
{
	// Mail verschicken
	include '../classes/class.phpmailer.php';

	// PHPMailer-Instanz erzeugen ("Wir machen ein neues "Mail")
	$m = new PHPMailer;

	// Absenderadresse
	$m->From = $von;

	// Absendername
	$m->FromName = Secrets::MailFromName;

	// Zieladresse
	$m->addAddress($an);

	// Betreff
	$m->Subject = $betreff;

	// Inhalt angeben
	$m->Body = $mail_inhalt;

	// XX Reine Textversion
	//$m->AltBody = $reiner_text ;

	// He du Mail, du bist ein HTML-Mail
	$m->isHTML(true);

	// Kodierung setzen
	$m->CharSet = 'utf-8';

	// Absenden
	$m->Send();

	// Speicher wieder frei geben
	$m = NULL;
}
/** 
 * Datum schön formatieren
 **/

// Unixformat
function schoenes_datum_unix($datumswert)
{

	// Datumswert in Sekunden seit dem 1.1.1970 umwandeln

	//$sekunden = strtotime( $datumswert );  // Wandelt 2014-12-10... in Sekundenzahl um, kann auch tomorrow oder two times ago umwandeln    

	// formatieren

	$formatiert = strftime('%a, %e. %B %Y', $datumswert);

	// zurückliefern

	return $formatiert;
}

//Datumsformat yyyy-mm-dd

function schoenes_datum_engl($datumswert)
{

	// Datumswert in Sekunden seit dem 1.1.1970 umwandeln

	$sekunden = strtotime($datumswert);  // Wandelt 2014-12-10... in Sekundenzahl um, kann auch tomorrow oder two times ago umwandeln    

	// formatieren

	$formatiert = strftime('%a, %e. %B %Y', $sekunden);

	// zurückliefern

	return $formatiert;
}

/**

 * Datenbank abfragen, als einziges Argument wird die Frage selbst mitgegeben

 * die Anfrage ist ein SQL-Statement

 **/

function db_anfragen($sql)
{

	// Zur Datenbank verbinden (es gibt auch mysql_connect, aber mit i ist improved

	$db = mysqli_connect(Secrets::DbHost, Secrets::DbUser, Secrets::DbPassword, Secrets::DbName);

	// Übertragung der Daten von der DB zu PHP auch als UTF-8, bitte

	$db->query('SET NAMES "UTF8"');

	// Alle aktuellen Beiträge holen

	$res = $db->query($sql);         // Liefert zeiger zurück (hol sie selber)

	// Verbindung schliessen

	$db->close();

	// Zeiger auf das resultat zurückliefern

	return $res;
}

function eindeutiger_dateiname($pfad)
{

	// Beispielpfad: 'uploads/galerie/schöner südsee.Strand.jpg'

	// Ordner ermitteln, ohne Slash am Ende

	$ordner = dirname($pfad);  				// uploads/galerie

	// Dateiname, wie ihn der Benutzer angegeben hat

	$datei_name = basename($pfad);  			// schöner südsee.Strand.jpg

	// Dateiendung ermitteln: dazu "sprengen" wir den Dateinamen bei jedem Punkt

	$teile = explode('.', $datei_name);    	// ergibt Array, bestehend aus "schö'ner südsee", "Strand", "jpg"

	// Endung ist das letzte Element. dieses holen und speichern

	$endung = array_pop($teile);				// Endung ist "jpg"

	// 		restliche Elemente wieder zusammensetzen

	$stamm_name = implode('-', $teile);		// ergibt "schöner südsee-Strand"

	// Deutsche sprachliche-regionale Umgebung einstellen (z. B. auch bei währungen, datum, etc.

	setlocale(LC_ALL, 'de_DE.UTF-8');

	// alles klein schreiben

	$stamm_name = strtolower($stamm_name);  										// ergibt "schoe-ner-suedsee-strand"

	// Alle Sonderzeichen in ASCII umwandeln (wir versuchens mal)

	$stamm_name = iconv("UTF-8", "ASCII//TRANSLIT", $stamm_name); 	// ergibt "schoe'ner suedsee-Strand"

	// leerschläge durch ein "-" ersetzen. Mehrere Leerschläge nacheinander durch ein einziges "-" ersetzen

	// Alternative: alles ersezten, was nicht a-z, A-Z oder 0-9 ist

	$stamm_name = preg_replace('/[^a-zA-Z0-9_]+/', '-', $stamm_name); 				// ergibt "schoe-ner-suedsee-Strand"

	// Zähler, damit wir später eventuell den Namen anpassen können

	$z = 0;

	// in der do-while-Schlaufe den Namen zusammenbauen und prüfen, obs den schon gibt

	do {

		// uploads/galerie/schoe-ner-suedsee-strand

		$neuer_name = $ordner . '/' . $stamm_name;

		if ($z > 0) {

			$neuer_name .= '-' . str_pad($z, 3, '0', STR_PAD_LEFT);   // füllt vorne auf, bis es drei ziffern sind

		}

		// endung anhängen

		$neuer_name .= '.' . $endung;

		// z erhöhen für den eventuellen nächsten Durchgang

		$z++;
	} while (file_exists($neuer_name));

	// Wir hanben einen eindeutigen Namen gefunden, diesen zurückliefern

	return $neuer_name;
}
