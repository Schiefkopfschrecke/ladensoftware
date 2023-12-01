<?php



	error_reporting(E_ALL);

// include functions, configurations and header
		 	include( 'inc/functions.php' );
		 	include( 'inc/login.php' );

		 	include( '../inc/config.php' );

		 	include( 'inc/header.php' );

// Start treating form
// check if form is posted

if ( $_POST ) {
	
	if ($_POST['produkt_id'] > 0) {
	//if Form is posted, treat form
	// insert to database
	$sql = 'UPDATE 
				produkte 
			SET
				produktname = "' . $_POST['produktname'] . '", lieferant_id = "' . $_POST['lieferant_id'] . '", produzent_id = "' . $_POST['produzent_id'] . '", einheit ="' . $_POST['einheit'] . '", ekp_pro_einheit = "' . $_POST['ekp_pro_einheit'] . '", kategorie = "' . $_POST['kategorie'] . '", bild = "' . $_POST['bild']  . '" WHERE id = ' . $_POST['produkt_id'];
	$res = db_anfragen( $sql );
	
	// if entry in DB is done create feedback on website an by email
	if ($res) {
		// create text for website
		echo '<div style="border:2px solid #e52300; padding:5px; "><b>Deine Daten wurden geändert</b></div>';

	
	};
	} else if ($_POST['produkt_id'] == 0) {
		$sql = 'INSERT INTO produkte ( produktname, lieferant_id, produzent_id, einheit, ekp_pro_einheit, kategorie, bild)
				VALUES (
						"' . $_POST['produktname'] . '", 
						"' . $_POST['lieferant_id'] . '", 
						"' . $_POST['produzent_id'] . '", 
						"' . $_POST['einheit'] . '", 
						"' . $_POST['ekp_pro_einheit'] . '", 
						"' . $_POST['kategorie'] . '", 
						"' . $_POST['bild'] . '"
				)';
		$res = db_anfragen($sql);
	if ($res) {
		// create text for website
		echo '<div style="border:2px solid #e52300; padding:5px; "><b>Produkt "' . $_POST['produktname'] . '" wurde eingefügt</b></div>';

	
	};
		
					
	}
} 


	$produkt_id = filter_input( INPUT_GET, 'produkt_id', FILTER_VALIDATE_INT );
	
	
		if ( $produkt_id === 0 ) {

		// Neuer leerer assoziativer Array vorbereiten

		$produktdaten = array(

			'produktname' => '', 

			'lieferant_id' => '', 

			'produzent_id' => '',

			'einheit' => '', 

			'ekp_pro_einheit' => '',
			'kategorie' => '',
			'bild' => ''



		);
	} else if ($produkt_id > 0) {

		$sql = 'SELECT * FROM produkte WHERE id = ' . $produkt_id;
		$res = db_anfragen($sql);
		$produktdaten = mysqli_fetch_assoc($res);
		}

echo '<div id="kontoaenderung"><form action="" method="post">

			<input type="hidden" name="produkt_id" id="produkt_id" value="' . $produkt_id . '">
			<table>
			<tr>
				<td>Produktname:*</td>
				<td><input type="text" name="produktname" id="produktname" required placeholder="Produktname*" value="' . $produktdaten['produktname'] . '"> </td></tr>
			<tr>
				<td>Lieferant:*</td>
				<td><input type="text" name="lieferant_id" id="lieferant_id" required placeholder="Lieferant*" value="' . $produktdaten['lieferant_id'] . '"> </td></tr>
			<tr>
				<td>Produzent:*</td>
				<td><input type="text" name="produzent_id" id="produzent_id" placeholder="Produzent*" value="' . $produktdaten['produzent_id'] . '"> </td></tr>
			<tr>
				<td>Einheit:*</td>
				<td><input type="text" name="einheit" id="einheit" required placeholder="Einheit*" value="' . $produktdaten['einheit'] . '"> </td></tr>
			<tr>
				<td>VP/Einheit:*</td>
				<td><input type="text" name="ekp_pro_einheit" id="ekp_pro_einheit" required placeholder="VP/Einheit*" value="' . $produktdaten['ekp_pro_einheit'] . '"> </td></tr>
			<tr>
				<td>Kategorie:*</td>
				<td><select id="kategorie" name="kategorie">'; 
		foreach ( $config['produktkategorien'] as $eine_kategorie) {
			echo '<option value="' . $eine_kategorie . '" ';
			if ( $eine_kategorie == $produktdaten['kategorie']){ echo 'selected';}
			echo '>' . $eine_kategorie . '</option>';
		}
				echo '</select></td></tr>
			<tr>
				<td>Bild:</td>
				<td><input type="text" name="bild" id="bild"  placeholder="Bild" value="' . $produktdaten['bild'] . '"> </td></tr>
			<tr>
				<td></td>
				<td><input type="submit" class="button" value="Ändern"> </td></tr>
	</table>

      

    

    </form></div>';




		 	include( 'inc/footer.php' );

		?>