<!doctype html>

<html>

<head>

	<meta charset="utf-8">

	<title>Bioladen Bern Felsenau</title>

	<link rel="shortcut icon" href="design/favicon.ico">



	<link rel="stylesheet" href="../design/style.css" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

	<!-- no caching -->
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<!-- FANCYBOX -->

	<!-- Add mousewheel plugin (this is optional) -->

	<script type="text/javascript" src="js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>



	<!-- Add fancyBox -->

	<link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />

	<script type="text/javascript" src="js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>



	<!-- Optionally add helpers - button, thumbnail and/or media -->

	<link rel="stylesheet" href="js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />

	<script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>



	<link rel="stylesheet" href="js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />

	<script type="text/javascript" src="js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- CSS für Responsive Design von Rude -->
	<link href="inc/normalize_rwd.css" rel="stylesheet" type="text/css" media="screen">

	<!-- RESPOND.JS MACHT MEDIA QUERIES FÜR DEN IE LESBAR -->
	<script src="../js/respond.js"></script>

	<!-- HTML5SHIV MACHT HTML5-TAGS IN ALTEN IE-BROWSERN LESBAR -->
	<script src="../js/html5shiv.js"></script>

	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="cleartype" content="on">

	<script src="js/meine-cms-scripts.js"></script>

</head>

<body>
	<div id="seite">
		<nav>
			<ul>
				<li><a href="index.php" title="Startseite" alt="">Startseite</li><
				<li><a href="einkaufen.php" title="Einkaufen" alt="">Einkaufen</a></li><
				<li><a href="mitgliedschaft.php" title="Mitglied werden" alt="">Mitglied werden</a></li>
				<li><a href="sortiment.php" title="Sortiment" alt="">Sortiment</a></li>
				<?php $mitgliederdaten = 0;

					if (isset($_SESSION['user']['id'])) {
						echo '<li><a href="logout.php" title="Logout" alt="">Abmelden</a></li>';

						// Wenn Mitglied Administrator ist, wird Admin angezeigt
						$sql = 'SELECT ist_admin FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
						$res = db_anfragen($sql);
						$mitgliederdaten = mysqli_fetch_assoc($res);
						if ($mitgliederdaten['ist_admin'] == "ja") {
							echo '<li><a href="admin/home.php" title="Adminbereich" alt="">Admin</a></li>';
						}
					}
				?>

			</ul>

		</nav>

		<section id="content">