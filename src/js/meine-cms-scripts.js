// JavaScript Document

// Wenn das DOM bereit ist

$( function() {


	// Im DOM alle A-Elemente suchen, die die Klasse "bestaetigen" zugeordnet haben
	// und darauf das Klick-Ereignis abfangen
	$( '.entfernen_bestaetigen' ).on( 'click', function() {

		return confirm( 'Willst du dieses Produkt aus dem Warenkorb entfernen?' );

	});
	$( '.entfernen_prod_bestaetigen' ).on( 'click', function() {

		return confirm( 'Willst du dieses Produkt/Produzent definitiv löschen?' );

	});
	
	$( '.nachbestellen_bestaetigen' ).on( 'click', function() {

		return confirm( 'Ist das Produkt fast aufgebraucht und muss bald nachgefüllt werden?\n(Löst eine Mail aus!)' );

	});

	
	$( "#datepicker" ).datepicker( {

		"dateFormat":  "dd.mm.yy" },

		$.datepicker.regional['de']

	);


	// Im DOM alle A-Elemente ansteuern, die die Klasse "einaus" haben

	// (für die Aktivierung/Deaktivierung der Beiträge via AJAX) und 

	// darauf das Klick-Ereignis abfangen

	// Ein/Aus für Aktiv

	$( 'a.einaus' ).on( 'click', function() {

		// ID des Beitrags ermitteln, die diesem Link 

		// im data-beitrags-id-Attribut zugeordnet ist

		var mein_link = $( this );

		var kontakt_id = mein_link.attr( 'data-kontakt-id' );

		// Neuer AJAX-Request senden

		$.ajax({

			'url': 'xhr/kontakt-ein-aus.php', 

			'type': 'get',

			'data': {

				'id': kontakt_id

			}

		}).done( function( antwort ) {

			// Linktext anpassen

			mein_link.text( antwort );

		});

		

		

		

		

		

	$(".jahr").change(function()

		{

		var id=$(this).val();

		var dataString = 'id='+ id;

		

		$.ajax

		({

		type: "POST",

		url: "xhr/dropdown.php",

		data: dataString,

		cache: false,

		success: function(html)

		{

		$(".city").html(html);

		}

		});

		

		});

	});

	

	

	

	

	

	

});