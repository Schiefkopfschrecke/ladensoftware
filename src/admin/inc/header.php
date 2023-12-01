<!doctype html>

<html>

<head>

	<meta charset="utf-8">

	<meta http-equiv="cache-control" content="no-cache">

	<title>GemeinSaftladen-Admin</title>

	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/start/jquery-ui.css" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

	<script type="text/javascript" src="js/jqueryui_de.js"></script>

	<script src="../../js/meine-cms-scripts.js"></script>

	<script src="inc/ckeditor/ckeditor.js"></script>

	<!-- Anfang Tabellensortierungstool Studpid Table -->
	<script src="../../js/stupid-table-plugin/stupidtable.js?dev"></script>
	<script>
		$(function() {
			// Helper function to convert a string of the form "Mar 15, 1987" into a Date object.
			var date_from_string = function(str) {
				var months = ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"];
				var pattern = "^([a-zA-Z]{3})\\s*(\\d{1,2}),\\s*(\\d{4})$";
				var re = new RegExp(pattern);
				var DateParts = re.exec(str).slice(1);

				var Year = DateParts[2];
				var Month = $.inArray(DateParts[0].toLowerCase(), months);
				var Day = DateParts[1];

				return new Date(Year, Month, Day);
			}

			var table = $("table").stupidtable({
				"date": function(a, b) {
					// Get these into date objects for comparison.
					aDate = date_from_string(a);
					bDate = date_from_string(b);
					return aDate - bDate;
				}
			});

			table.on("beforetablesort", function(event, data) {
				// Apply a "disabled" look to the table while sorting.
				// Using addClass for "testing" as it takes slightly longer to render.
				$("#msg").text("Sorting...");
				$("table").addClass("disabled");
			});

			table.on("aftertablesort", function(event, data) {
				// Reset loading message.
				$("#msg").html("&nbsp;");
				$("table").removeClass("disabled");

				var th = $(this).find("th");
				th.find(".arrow").remove();
				var dir = $.fn.stupidtable.dir;

				var arrow = data.direction === dir.ASC ? "&uarr;" : "&darr;";
				th.eq(data.column).append('<span class="arrow">' + arrow + '</span>');
			});
		});
	</script>
	<!-- Ende Tabellensortierungstool Studpid Table -->
	<link rel="stylesheet" type="text/css" href="admin.css" />

</head>

<body>

	<nav>

		<ul>

			<li <?php if (basename($_SERVER['PHP_SELF']) == 'home.php') {
						echo 'class="selected"';
					}  ?>><a href="home.php">Home</a><br>&nbsp;</li>
			<li <?php if (basename($_SERVER['PHP_SELF']) == 'produkte_verwalten.php') {
						echo 'class="selected"';
					}  ?>><a href="produkte_verwalten.php">Produkte <br>verwalten</a></li>
			<li <?php if (basename($_SERVER['PHP_SELF']) == 'produzenten_verwalten.php') {
						echo 'class="selected"';
					}  ?>><a href="produzenten_verwalten.php">Produzenten <br>verwalten</a></li>
			<li <?php if (basename($_SERVER['PHP_SELF']) == 'lieferanten_verwalten.php') {
						echo 'class="selected"';
					}  ?>><a href="lieferanten_verwalten.php">Lieferanten <br>verwalten</a></li>
			<li <?php if (basename($_SERVER['PHP_SELF']) == 'mitglieder_verwalten.php') {
						echo 'class="selected"';
					}  ?>><a href="mitglieder_verwalten.php">Mitglieder <br>verwalten</a></li>
			<li <?php if (basename($_SERVER['PHP_SELF']) == 'milchabo_verwalten.php') {
						echo 'class="selected"';
					}  ?>><a href="milchabo_verwalten.php">Milchabo <br>verwalten</a></li>
			<!--<li <?php if (basename($_SERVER['PHP_SELF']) == 'rechnungen_verwalten.php') {
								echo 'class="selected"';
							}  ?>><a href="rechnungen_verwalten.php">Rechnungen <br>verwalten</a></li>-->
			<li <?php if (basename($_SERVER['PHP_SELF']) == 'logout.php') {
						echo 'class="selected"';
					}  ?>><a href="../logout.php">Abmelden</a><br>&nbsp;</li>

		</ul>

	</nav>

	<section id="hauptinhalt">