<?php

include('inc/functions.php');
include('inc/config.php');
include('inc/header.php');

?>
<!--  jQuery und fancybox hinzufügen -->

<script src="js/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="js/fancybox/dist/jquery.fancybox.min.css" />
<script src="js/fancybox/dist/jquery.fancybox.min.js"></script>

<div id="ueberuns">
	<form method="post" action="login.php">
		<p>
			<input type="text" name="benutzername" id="benutzername" placeholder="Benutzername" maxlength="200" autocomplete="off" autofocus />
			<input type="password" name="passwort" id="passwort" placeholder="Passwort" maxlength="20" autocomplete="off" />
			<input type="submit" value="Login" />
			<br>
			<a href="passwort_vergessen.php" style="font-size: 75%">Benutzername & Passwort vergessen?</a>
		</p>
	</form>

	<?php
	// Falls Reduzierte Artikel vorhanden sind, Daten des Produktes aufrufen und ausgeben
	$sql = 'SELECT vp_pro_einheit, rabatt, produktname FROM produkte WHERE rabatt < 1 ';
	$res = db_anfragen($sql);
	if (mysqli_num_rows($res) > 0) {
		echo '<h2>Reduktion auf folgende Produkte</h2>';
	}
	while ($rabattprodukte = mysqli_fetch_assoc($res)) {
		echo $rabattprodukte['produktname'] . ' (' . $rabattprodukte['rabatt'] * 100 . '%), ';
	}
	$produktinfo = mysqli_fetch_assoc($res);
	?>

	<h1>Der GemeinSaftladen</h1>
	<p>Wir sind ein Lebensmittellager das seinen Mitgliedern rund um die Uhr nachhaltige Produkte für den Alltag zu günstigen Preisen anbietet. Unsere Devise: regional, saisonal, biologisch, möglichst unverpackt. <br> Um uns besser kennen zu lernen, kannst du unseren schönen <a href="/downloads/Visionstext_GemeinSaftladen.pdf" title="Visionstext als PDF">Visionstext</a> oder die <a href="downloads/Statuten_Gemeinsaftladen.pdf" titel="Statuten" target="_blank">Statuten</a> lesen.</p>
	<p>Da unser Laden klein ist und wir die lokale Vernetzung fördern wollen, sind wir in erster Linie für Belebende der Felsenau und dem Rossfeld da. Falls du in einem anderen Quartier wohnst, <a href="mailto:info@gemeinsaftladen.ch">schreibe uns</a> doch kurz deine Motivation, warum du bei uns Mitglied werden willst. Wir machen Ausnahmen :-).</p>
	<p>Du kannst hier <a href="mitgliedschaft.php" title="Mitglied werden">Mitglied werden</a> und auf dem <a href="/downloads/Merkblatt_wie_einkaufen.pdf" title="Merkblatt einkaufen" target="_blank">Einkaufsmerkblatt</a> nachlesen, wie ein Einkauf funktioniert.</p>
	<p>Falls du einen eigenen Laden gründen möchtest: wir geben alle unsere Dokumente gerne weiter: Schreibe uns eine <a href="mailto:info@gemeinsaftladen.ch">Mail</a>!</p>
	<p>Oder kaufe in folgenden Bioläden (mit und ohne Mitgliedschaft) ein:</p>
	<ul>
		<li><a href='https://www.q-laden.ch' target='_blank'>Q-Laden, Bern, Lorraine, öffentlich</a></li>
		<li>
			<a href='https://hallerladen.ch/' target='_blank'>Hallerladen, Bern, Länggasse, öffentlich</a>
		</li>
		<li>
			<a href='https://www.contact-arbeit.ch/lola-lorraineladen/' target='_blank'>LoLa, Bern, Lorraine, öffentlich</a>
		</li>
		<li>
			<a href='https://bern-unverpackt.ch/' target='_blank'>Bern unverpackt, Bern, Eigerplatz, öffentlich</a>
		</li>
		<li>
			<a href='https://palette-bern.ch/' target='_blank'>Palette, Bern, Altstadt, öffentlich</a>
		</li>
		<li>
			<a href='http://ladenimmurifeld.ch/' target='_blank'>Laden im Murifeld, Bern, öffentlich</a>
		</li>
		<li>
			<a href='https://dorfladenmittelhaeusern.wordpress.com/' target='_blank'>Dorfladen Mittelhäusern, Mittelhäusern, öffentlich</a>
		</li>
		<li>
			<a href='' target='_blank'>Depot8, Bern, Warmbächli, Mitgliederladen</a>
		</li>
		<li>
			<a href='https://www.gueter.be/' target='_blank'>Güter, Bern, Eigerplatz, Mitgliederladen</a>
		</li>
		<li>
			<a href='https://depothuber.ch/' target='_blank'>Depot Huber, Bern, Ausserholligen, Mitgliederladen</a>
		</li>
		<li>
			<a href='https://www.urbanedoerfer.ch' target='_blank'>Urbane Dörfer, Zollikofen, in Gründungsphase</a>
		</li>
		<li>
			<a href='https://www.koemerle.ch' target='_blank'>Kömerle, Jegenstorf, in Gründungsphase</a>
		</li>
	</ul>


	<h2>Reinigung/Hygiene</h2>
	<p>Damit der GemeinSaftladen sauber bleibt, haben wir einen Reinigungsplan. Jedes Mitglied sollte 1-2 Mal pro Jahr den GemeinSaftladen reinigen (pro Reinigungseinsatz ca. 1 Stunde einrechnen). An der Türe zum Laden hängt ein Kalender, in welche du dich bitte einträgst. Wenn du die Reinigung gemacht hast, bitte auf dem Reinigungskalender abhaken.</p>
	<h2>Infos/Merkblätter</h2>
	<ul>
		<li><a href="/downloads/Merkblatt_Reinigung.pdf" title="Merkblatt Reinigung" target="_blank">Reinigungsanleitung</a>
		<li><a href="/downloads/Merkblatt_wie_einkaufen.pdf" title="Merkblatt wie einkaufen" target="_blank">Merkblatt Einkaufen</a></li>
		<li><a href="/downloads/Merkblatt_Leergut.pdf" title="Merkblatt Leergut" target="_blank">Merkblatt Leergut</a></li>
		<li><a href="/downloads/Merkblatt_Getreidemühle.pdf" title="Merkblatt Getreidemühle" target="_blank">Merkblatt Getreidemühle</a></li>
		<li><a href="downloads/Bestellformular_Milchprodukte.docx" title="Bestellformular Milchprodukte" target="_blank">Bestellformular Milchprodukte</a></li>
	</ul>
	<h2>Kontakte für Früchte/Gemüse/Brot-Abos</h2>
	<ul>
		<li><a href="https://tapatate.ch/de/index.html#mitmachen" target="_blank">Gemüse- und Früchteabo von Tapatate</a></li>
		<li><a href="https://soliterre.ch/index.php/gemuese-abo" target="_blank">Gemüse-, Eier-, Kartoffel-, ...-Abo von Soliterre, Lieferung am Mittwoch</a></li>
		<li><a href="http://heimenhaus.ch/gemuse-abo" target="_blank">Gemüseabo von Heimenhaus</a></li>
		<li><a href="http://www.reformbaeckerei.ch/" target="_blank">Brotabo der Reformbäckerei Vechigen</a>; Bestellung über <a href="downloads/Reformbaeckerei_Bestellliste_2021_Felsenau.xlsx">Brotformular</a>, Lieferung Mittwochs in Annex</li>
	</ul>
	<h2>News</h2>
	<ul>
		<li><a href="https://mailchi.mp/68104e00cdf0/newsletter-august-2022" title="Newsletter August" target="_blank">August-Newsletter: Defizit & Schwund, GV, Signal-Gruppe, Geschirrspühlmittel</a></li>
		<li><a href="https://mailchi.mp/f2d2ed40d5cc/newsletter-mai-2022" title="Newsletter Mai" target="_blank">Mai-Newsletter: MV, Guthaben aufladen, Umfrage Reinigung, Olivenöl, TaPatate!, Plastik sammeln</a></li>
		<li><a href="https://mailchi.mp/3ebfb65edc8a/newsletter-november-21" title="Newsletter November" target="_blank">November-Newsletter: Nasser Sommern, Nachbestellung, Reinigung</a></li>
		<li><a href="https://mailchi.mp/80fa5d88e240/newsletter-september-2021" title="Newsletter September" target="_blank">September-Newsletter: Gewinn an Mitgliederläden, Ausleihbörse, TexMix & Biodiversitätstag</a></li>
		<li><a href="https://mailchi.mp/53146410dc92/newsletter-juli-2021" title="Newsletter Juli" target="_blank">Juli-Newsletter: Umfrage Schlüsselkasten, Gewinnworkshop, Protokoll GV, Produkteverfügbarkeit</a></li>
		<li><a href="https://mailchi.mp/61787bbb63ca/newsletter-mai" title="Newsletter Mai" target="_blank">Mai-Newsletter: Haferdrink, Leergut, Lieferkontakt, Schlüsselkasten, Abos</a></li>
		<li><a href="https://mailchi.mp/002da5309431/newsletter-april-2021" title="Newsletter April" target="_blank">April-Newsletter: Reinigung, Kontoaufladen</a></li>
		<li><a href="downloads/newsletter/2103_Newsletter.pdf" title="Newsletter März" target="_blank">März-Newsletter: Inventur, Gewürze, Arenaartikel</a></li>
		<li><a href="downloads/newsletter/2101_Newsletter.pdf" title="Newsletter Januar" target="_blank">Januar-Newsletter: Vorstand, zu wegen geschlossen, Milchprodukte, Wunschtafel, Kaffee</a></li>
		<li><a href="downloads/newsletter/2011_Newsletter.pdf" title="Newsletter November" target="_blank">November-Newsletter: Sesamrückruf, GSL in den Medien, Mitarbeit, Hazelburger</a></li>
		<li><a href="https://mailchi.mp/55c6fa719026/newsletter-herbst-2020" title="Newsletter September" target="_blank">September-Newsletter</a></li>
		<li><a href="downloads/newsletter/2008_Newsletter.pdf" title="Newsletter August" target="_blank">August-Newsletter</a></li>
		<li><a href="https://mailchi.mp/94e9fe7f590a/saftige-news-im-april-4455772" title="Newsletter Juli" target="_blank">Juli-Newsletter</a></li>
		<li><a href="downloads/newsletter/2020-06-19 Juni Codewechsel und andere News.png" title="Newsletter Mai" target="_blank">Juni-Newsletter: Codewechsel, GV, Hanfsamen ...</a></li>
		<li><a href="https://mailchi.mp/68740300019e/saftige-news-im-april-4409920" title="Newsletter Mai" target="_blank">Mai-Newsletter</a></li>
		<li><a href="https://mailchi.mp/35d7ac64f444/saftige-news-im-april" title="Newsletter April" target="_blank">April-Newsletter "Saftige News im April"</a></li>
	</ul>
	<h2>Medien</h2>
	<ul>
		<li><a href="downloads/Artikel_GSL_BZ.pdf" title="GSL in der Bernerzeitung" target="_blank">GSL in der Bernerzeitung</a></li>
		<li><a href="downloads/Artikel_Arena.jpg" title="GSL in der Arena" target="_blank">GSL in der Arena</a></li>
	</ul>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox-gallery").fancybox({
				prevEffect: 'none',
				nextEffect: 'none',
				closeBtn: true,
				helpers: {
					title: {
						type: 'inside'
					},
					overlay: {
						css: {
							'background-color': '#E4E4E4'
						}
					},
					buttons: {}
				}
			});
		});
	</script>
	<h2>Fotos</h2>
	<a data-fancybox="gallery" href="fotos/IMG_20200313_124603.jpg" title="Konserven und Getränke"><img src="fotos/IMG_20200313_124603_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200313_173049.jpg" title="Beschrieb"><img src="fotos/IMG_20200313_173049_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200313_173101.jpg" title="Beschrieb"><img src="fotos/IMG_20200313_173101_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200313_173337.jpg" title="Beschrieb"><img src="fotos/IMG_20200313_173337_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200313_173638.jpg" title="Beschrieb"><img src="fotos/IMG_20200313_173638_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200313_173645.jpg" title="Beschrieb"><img src="fotos/IMG_20200313_173645_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200313_195236.jpg" title="Beschrieb"><img src="fotos/IMG_20200313_195236_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200401_174413.jpg" title="Beschrieb"><img src="fotos/IMG_20200401_174413_200px.jpg" height="200px"></a>
	<a data-fancybox="gallery" href="fotos/IMG_20200401_175225.jpg" title="Beschrieb"><img src="fotos/IMG_20200401_175225_200px.jpg" height="200px"></a>


	<!--Karte mit GemeinSaftladen
	<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=7.440815269947053%2C46.96840402313304%2C7.443856894969941%2C46.96970172102235&amp;layer=mapnik&amp;marker=46.96905287601325%2C7.442336082458496" style="border: 1px solid black"></iframe><br/><a href="https://www.openstreetmap.org/?mlat=46.96905&amp;mlon=7.44234#map=19/46.96905/7.44234" target="_blank"><small>Größere Karte anzeigen</small></a>-->
</div>

<?php

include('inc/footer.php');

?>