<?php

error_reporting(E_ALL);

include  'inc/functions.php' ;
include  'inc/login.php' ;
include  'inc/config.php' ;

if ( $_POST ) {
		$mitglied_id = filter_input( INPUT_POST, 'mitglied_id', FILTER_VALIDATE_INT );

	// Empfangene Daten säubern
	// Total mit -1 multiplizieren weil Einkauf
		$total = filter_input( INPUT_POST, 'total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) * -1;
	$total = number_format( $total, 2, '.', '\'');
		$quittung = filter_input(INPUT_POST, 'quittung', FILTER_SANITIZE_NUMBER_INT );

	// XX Einfügen von Kontrolle der Eingaben und Reinigung
	$zeit = date('Y-m-d H:i:s');
	$sql = 'INSERT INTO 
				buchungen_mitglieder (zeit, mitglied_id, preis, bucher_id, betreff )
			VALUES (
				"' . $zeit . '",
				"' . $mitglied_id . '",
				"' . $total . '",
				"' . $mitglied_id . '",
				"Einkauf Website"
				)';

	$res = db_anfragen( $sql );
	
	$sql = 'UPDATE 
				warenkorb
			SET
				eingekauft = "' . $zeit . '" 
			WHERE 
				mitglied_id = ' . $mitglied_id . ' AND eingekauft = 0';
	
	$res2 = db_anfragen( $sql );	
}

header( 'Location:kauf_abgeschlossen.php' );
