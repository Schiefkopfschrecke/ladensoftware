<?php

/* Konfiguration für die ganze Seite,
	 * Zentrale Werte, die überall irgendwie gebraucht werden
	*/

// Deutschsprachige Umgebung einstellen
setlocale(LC_ALL, 'de_DE.utf-8');

$config = array();

// Status der Mitglieder
$config['mitgliedstatus'] = array('Mitgliedschaft beantragt', 'berechtigt', 'gesperrt', 'ausgetreten');

$config['buchungstexte'] = array('Einzahlung(+)', 'Milchprodukteabo(-)', 'Vorbestellung(-)', 'Spesen Produkte(+)', 'Gutschein(+)', 'Subvention Mitgl(+)', 'Spende an GSL (-)');

$config['produktkategorien'] = array('Trockenprodukte', 'Kühlprodukte', 'Eier', 'Gemüse/Früchte', 'Non-Food', 'Getränke', 'Brot', 'Fleisch');

$config['ausleihkategorien'] = array('Bücher', 'Diverses', 'Elektronikgeräte', 'Gartengeräte', 'Haushaltgeräte', 'Kinderplunder', 'Küchengeräte', 'Musikinstrumente', 'Spiele', 'Sportutensilien', 'Verkehrsmittel (E-Bike, Auto, Anhänger ...)', 'Werkzeuge');
