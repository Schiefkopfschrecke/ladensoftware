<?php

error_reporting(E_ALL);

// include functions, configurations and header
include  'inc/functions.php';
include  'inc/login.php';
include  'inc/config.php';
include  'inc/header.php';

// D: ID filtern. In der Adresszeile steht etwas wie "beitraege.php?id=123"

//	  Die ID ist danach entweder eine Ganzzahl oder FALSE

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$res = db_anfragen('SELECT * FROM gegenstaende WHERE id = ' . $id);

// Diesen Datensatz als assoziativen Array bereitstellen

$gegenstand = mysqli_fetch_assoc($res);

//#####################

// Edit-angaen bearbeiten

// Sonst, falls die ID genau der Zahl 0 entspricht, soll ein neuer Datensatz vorbereitet werden

// Formular

?>

<form method="post" enctype="multipart/form-data">
	<h2>Gegenstand erfassen/ändern</h2>
	<input type="hidden" name="id" value="<?php echo $id; ?>" />

	<table>
		<tr>
			<td>Bezeichnung:*</td>
			<td><?php echo htmlspecialchars(stripslashes($gegenstand['bezeichnung'])); ?> </td>
		</tr>

		<tr>
			<td>Beschrieb:*</td>
			<td><?php echo htmlspecialchars(stripslashes($gegenstand['beschrieb'])); ?> </td>
		</tr>
		<tr>
			<td>Bedingungen:*</td>
			<td><?php echo htmlspecialchars(stripslashes($gegenstand['bedingungen'])); ?> </td>
		</tr>
		<tr>
			<td>Ausleiher*in:*</td>
			<td><?php echo htmlspecialchars(stripslashes($gegenstand['kontakt_name'])); ?> </td>
		</tr>
		<tr>
			<td>Telefon:*</td>
			<td><?php echo '<a href="tel:' . htmlspecialchars(stripslashes($gegenstand['kontakt_telefon'])) . '">' . htmlspecialchars(stripslashes($gegenstand['kontakt_telefon'])); ?></td>
		</tr>
		<tr>
			<td>Email:*</td>
			<td><?php echo '<a href="mailto:' . htmlspecialchars(stripslashes($gegenstand['kontakt_email'])) . '">' . htmlspecialchars(stripslashes($gegenstand['kontakt_email'])); ?></a></td>
		</tr>
	</table>
	<p><a href="ausleihboerse.php"><input type="button" value="Abbrechen" action="ausleihboerse.php" /></a></p>

	</div>

	<?php

	include  'inc/footer.php';

	?>