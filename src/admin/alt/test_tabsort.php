<!DOCTYPE html>
<html>
<head>
  <title>Stupid jQuery table sort (large table example)</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="../js/stupid-table-plugin/stupidtable.js?dev"></script>
  <script>
    $(function(){
        // Helper function to convert a string of the form "Mar 15, 1987" into a Date object.
        var date_from_string = function(str) {
          var months = ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"];
          var pattern = "^([a-zA-Z]{3})\\s*(\\d{1,2}),\\s*(\\d{4})$";
          var re = new RegExp(pattern);
          var DateParts = re.exec(str).slice(1);

          var Year = DateParts[2];
          var Month = $.inArray(DateParts[0].toLowerCase(), months);
          var Day = DateParts[1];

          return new Date(Year, Month, Day);
        }

        var table = $("table").stupidtable({
          "date": function(a,b) {
            // Get these into date objects for comparison.
            aDate = date_from_string(a);
            bDate = date_from_string(b);
            return aDate - bDate;
          }
        });

        table.on("beforetablesort", function (event, data) {
          // Apply a "disabled" look to the table while sorting.
          // Using addClass for "testing" as it takes slightly longer to render.
          $("#msg").text("Sorting...");
          $("table").addClass("disabled");
        });

        table.on("aftertablesort", function (event, data) {
          // Reset loading message.
          $("#msg").html("&nbsp;");
          $("table").removeClass("disabled");

          var th = $(this).find("th");
          th.find(".arrow").remove();
          var dir = $.fn.stupidtable.dir;

          var arrow = data.direction === dir.ASC ? "&uarr;" : "&darr;";
          th.eq(data.column).append('<span class="arrow">' + arrow +'</span>');
        });
    });
  </script>
  <style type="text/css">
    body {
      font-family: "Ubuntu", "Trebuchet MS", sans-serif;
    }
    table {
      border-collapse: collapse;
      margin: 1em auto;
    }
    th, td {
      padding: 5px 10px;
      border: 1px solid #999;
      font-size: 12px;
    }
    th {
      background-color: #eee;
    }
	
    th[data-sort]{
      cursor:pointer;
    }

    /* just some random additional styles for a more real-world situation */
    #msg {
      color: #0a0;
      text-align: center;
    }
    td.name {
      font-weight: bold;
    }
    td.email {
      color: #666;
      text-decoration: underline;
    }
    /* zebra-striping seems to really slow down Opera sometimes */
    tr:nth-child(even) > td {
      background-color: #f9f9f7;
    }
    tr:nth-child(odd) > td {
      background-color: #ffffff;
    }
    .disabled {
      opacity: 0.5;
    }
  </style>
</head>

<body>

  <h1>Stupid jQuery table sort! (large table example)</h1>

  <p>This is a large table of over 500 rows to show the plugin can handle large data sets with ease. It includes a mix of styling to mimic a more realistic website scenario. It also provides a better example of the <code>beforetablesort</code> callback by styling the table to appear <q>disabled</q> during sorting.</p>

  <p id="msg">&nbsp;</p>

  <!-- data taken from generatedata.com -->
  <table>
    <thead>
      <tr>
        <th data-sort="string">First name</th>
        <th data-sort="string">Last name</th>
        <th data-sort="string">City</th>
        <th data-sort="string">Country</th>
        <th>Email</th>
        <th data-sort="date">Registered</th>
        <th data-sort="int">ID</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Emmanuel</td>
        <td class="name">Owen</td>
        <td>Needham</td>
        <td>Pakistan</td>
        <td class="email">elit&#64;aliquetdiam.com</td>
        <td>Nov 18, 2011</td>
        <td>17321</td>
      </tr>
      <tr>
        <td>Stewart</td>
        <td class="name">Dillard</td>
        <td>South Portland</td>
        <td>Italy</td>
        <td class="email">justo.Proin.non&#64;utmolestie.ca</td>
        <td>Dec 30, 2012</td>
        <td>94003</td>
      </tr>
      <tr>
        <td>Tana</td>
        <td class="name">Villarreal</td>
        <td>Waltham</td>
        <td>Solomon Islands</td>
        <td class="email">Proin.eget&#64;tinciduntvehicula.edu</td>
        <td>Mar 25, 2012</td>
        <td>44041</td>
      </tr>
      <tr>
        <td>Wendy</td>
        <td class="name">Greer</td>
        <td>Bellflower</td>
        <td>Mauritania</td>
        <td class="email">arcu&#64;Duis.org</td>
        <td>Mar 6, 2011</td>
        <td>80251</td>
      </tr>
      <tr>
        <td>Kenneth</td>
        <td class="name">Livingston</td>
        <td>Anaheim</td>
        <td>Honduras</td>
        <td class="email">dolor.sit.amet&#64;purus.ca</td>
        <td>Jun 20, 2012</td>
        <td>79773</td>
      </tr>
      <tr>
        <td>Holly</td>
        <td class="name">Strong</td>
        <td>Placentia</td>
        <td>Greenland</td>
        <td class="email">Sed.eget.lacus&#64;mollisDuis.edu</td>
        <td>Jul 22, 2012</td>
        <td>56903</td>
      </tr>
      <tr>
        <td>Lynn</td>
        <td class="name">Cooley</td>
        <td>Temecula</td>
        <td>Papua New Guinea</td>
        <td class="email">Quisque.ornare.tortor&#64;senectusetnetus.com</td>
        <td>Apr 12, 2012</td>
        <td>68541</td>
      </tr>
      <tr>
        <td>Shafira</td>
        <td class="name">Valdez</td>
        <td>Columbus</td>
        <td>Taiwan, Province of China</td>
        <td class="email">Praesent&#64;erosnec.org</td>
        <td>Aug 15, 2011</td>
        <td>67777</td>
      </tr>
      <tr>
        <td>Autumn</td>
        <td class="name">Barry</td>
        <td>Malden</td>
        <td>Serbia and Montenegro</td>
        <td class="email">eget.lacus&#64;et.com</td>
        <td>Oct 19, 2011</td>
        <td>32595</td>
      </tr>
      <tr>
        <td>Hadassah</td>
        <td class="name">Berry</td>
        <td>Ketchikan</td>
        <td>Egypt</td>
        <td class="email">ligula.Aenean.euismod&#64;metus.com</td>
        <td>May 29, 2012</td>
        <td>58898</td>
      </tr>
      <tr>
        <td>Hector</td>
        <td class="name">Burns</td>
        <td>Kokomo</td>
        <td>Monaco</td>
        <td class="email">dolor.Nulla.semper&#64;atiaculisquis.edu</td>
        <td>Jun 22, 2012</td>
        <td>44279</td>
      </tr>
      <tr>
        <td>Eagan</td>
        <td class="name">Carr</td>
        <td>Jeannette</td>
        <td>Slovakia</td>
        <td class="email">pede.Cras.vulputate&#64;felis.org</td>
        <td>May 3, 2011</td>
        <td>52817</td>
      </tr>
      <tr>
        <td>Charissa</td>
        <td class="name">Barker</td>
        <td>Meadville</td>
        <td>New Zealand</td>
        <td class="email">eu&#64;duiquisaccumsan.edu</td>
        <td>Jun 18, 2012</td>
        <td>20900</td>
      </tr>
      <tr>
        <td>Abigail</td>
        <td class="name">Holman</td>
        <td>Dubuque</td>
        <td>Kiribati</td>
        <td class="email">ultrices&#64;semper.ca</td>
        <td>Nov 28, 2011</td>
        <td>36026</td>
      </tr>
      <tr>
        <td>Caesar</td>
        <td class="name">Carver</td>
        <td>Jordan Valley</td>
        <td>Mexico</td>
        <td class="email">tristique.ac.eleifend&#64;nequetellus.com</td>
        <td>Feb 1, 2012</td>
        <td>14537</td>
      </tr>
      <tr>
        <td>Jade</td>
        <td class="name">Juarez</td>
        <td>Billings</td>
        <td>Zimbabwe</td>
        <td class="email">volutpat&#64;Proin.ca</td>
        <td>May 12, 2012</td>
        <td>40574</td>
      </tr>
      <tr>
        <td>Barbara</td>
        <td class="name">Shields</td>
        <td>Saint Joseph</td>
        <td>Germany</td>
        <td class="email">dui&#64;Quisquefringilla.org</td>
        <td>Dec 7, 2011</td>
        <td>48920</td>
      </tr>
      <tr>
        <td>Rose</td>
        <td class="name">Pace</td>
        <td>Moraga</td>
        <td>Ecuador</td>
        <td class="email">iaculis&#64;nasceturridiculus.org</td>
        <td>Apr 12, 2011</td>
        <td>92908</td>
      </tr>
      <tr>
        <td>Nero</td>
        <td class="name">William</td>
        <td>Hutchinson</td>
        <td>Serbia and Montenegro</td>
        <td class="email">euismod.enim.Etiam&#64;sapien.com</td>
        <td>Dec 30, 2011</td>
        <td>10398</td>
      </tr>
      <tr>
        <td>Lucy</td>
        <td class="name">Mcclain</td>
        <td>South El Monte</td>
        <td>Holy See (Vatican City State)</td>
        <td class="email">elementum.sem.vitae&#64;purus.org</td>
        <td>Jun 17, 2012</td>
        <td>75898</td>
      </tr>
      <tr>
        <td>Thor</td>
        <td class="name">Kelly</td>
        <td>Jeffersonville</td>
        <td>Liberia</td>
        <td class="email">pellentesque.massa.lobortis&#64;Sed.com</td>
        <td>Nov 11, 2011</td>
        <td>59789</td>
      </tr>
      <tr>
        <td>Edward</td>
        <td class="name">Barron</td>
        <td>Mandan</td>
        <td>Paraguay</td>
        <td class="email">sed.dolor.Fusce&#64;elementum.ca</td>
        <td>Mar 13, 2011</td>
        <td>74375</td>
      </tr>
      <tr>
        <td>Aaron</td>
        <td class="name">Hansen</td>
        <td>Florence</td>
        <td>Svalbard and Jan Mayen</td>
        <td class="email">ligula.Aenean.euismod&#64;dolornonummyac.org</td>
        <td>Jun 2, 2012</td>
        <td>70820</td>
      </tr>
      <tr>
        <td>Mohammad</td>
        <td class="name">Mcfadden</td>
        <td>Cicero</td>
        <td>Bolivia</td>
        <td class="email">nunc&#64;vulputateullamcorpermagna.com</td>
        <td>Sep 16, 2011</td>
        <td>23056</td>
      </tr>
      <tr>
        <td>Mia</td>
        <td class="name">Marshall</td>
        <td>Columbia</td>
        <td>Colombia</td>
        <td class="email">gravida&#64;nibhsitamet.edu</td>
        <td>Aug 21, 2012</td>
        <td>52458</td>
      </tr>
      <tr>
        <td>Chester</td>
        <td class="name">Alvarez</td>
        <td>Springfield</td>
        <td>Lesotho</td>
        <td class="email">augue.eu.tellus&#64;semegestasblandit.org</td>
        <td>Oct 29, 2012</td>
        <td>44765</td>
      </tr>
      <tr>
        <td>Kelsey</td>
        <td class="name">Douglas</td>
        <td>Winnemucca</td>
        <td>Pitcairn</td>
        <td class="email">diam.Pellentesque&#64;sagittisDuis.edu</td>
        <td>Apr 5, 2011</td>
        <td>90683</td>
      </tr>
      <tr>
        <td>Erin</td>
        <td class="name">Sims</td>
        <td>La Habra</td>
        <td>Liberia</td>
        <td class="email">ac&#64;egestaslacinia.edu</td>
        <td>Jan 28, 2012</td>
        <td>57282</td>
      </tr>
      <tr>
        <td>Colt</td>
        <td class="name">Harper</td>
        <td>Mayagüez</td>
        <td>Bangladesh</td>
        <td class="email">lacus.Nulla.tincidunt&#64;idanteNunc.com</td>
        <td>Jul 13, 2011</td>
        <td>34013</td>
      </tr>
      <tr>
        <td>Xantha</td>
        <td class="name">Ross</td>
        <td>Lufkin</td>
        <td>United States Minor Outlying Islands</td>
        <td class="email">Nulla.facilisis&#64;eu.edu</td>
        <td>Aug 6, 2012</td>
        <td>26764</td>
      </tr>
      <tr>
        <td>Aiko</td>
        <td class="name">Gill</td>
        <td>Asbury Park</td>
        <td>Kyrgyzstan</td>
        <td class="email">tincidunt.aliquam.arcu&#64;dui.ca</td>
        <td>Jan 15, 2012</td>
        <td>45134</td>
      </tr>
      <tr>
        <td>Stacey</td>
        <td class="name">Barron</td>
        <td>Salem</td>
        <td>India</td>
        <td class="email">sed&#64;purusmaurisa.edu</td>
        <td>Apr 3, 2012</td>
        <td>16321</td>
      </tr>
      <tr>
        <td>Aurora</td>
        <td class="name">Craig</td>
        <td>Stillwater</td>
        <td>Morocco</td>
        <td class="email">tristique&#64;Praesenteu.com</td>
        <td>Aug 23, 2012</td>
        <td>55429</td>
      </tr>
      <tr>
        <td>Geoffrey</td>
        <td class="name">Kirby</td>
        <td>Sonoma</td>
        <td>Heard Island and Mcdonald Islands</td>
        <td class="email">lectus.Cum.sociis&#64;aliquetvel.edu</td>
        <td>Feb 11, 2012</td>
        <td>36110</td>
      </tr>
      <tr>
        <td>Kylynn</td>
        <td class="name">Sweeney</td>
        <td>Gilbert</td>
        <td>Greece</td>
        <td class="email">nulla&#64;est.com</td>
        <td>Mar 27, 2011</td>
        <td>31878</td>
      </tr>
      <tr>
        <td>Celeste</td>
        <td class="name">Gilliam</td>
        <td>Ketchikan</td>
        <td>Armenia</td>
        <td class="email">lobortis.tellus.justo&#64;asollicitudin.ca</td>
        <td>Oct 18, 2011</td>
        <td>90753</td>
      </tr>
      <tr>
        <td>Travis</td>
        <td class="name">Buckner</td>
        <td>Hot Springs</td>
        <td>Saint Pierre and Miquelon</td>
        <td class="email">erat.volutpat&#64;pharetraut.org</td>
        <td>Sep 1, 2012</td>
        <td>50696</td>
      </tr>
      <tr>
        <td>Deanna</td>
        <td class="name">Buckner</td>
        <td>Gloversville</td>
        <td>Mongolia</td>
        <td class="email">dolor.tempus&#64;quis.org</td>
        <td>Mar 6, 2012</td>
        <td>36838</td>
      </tr>
      <tr>
        <td>Nicholas</td>
        <td class="name">Vang</td>
        <td>North Chicago</td>
        <td>Cameroon</td>
        <td class="email">elit.pretium.et&#64;nisiMaurisnulla.ca</td>
        <td>Jun 10, 2012</td>
        <td>57392</td>
      </tr>
      <tr>
        <td>Dominic</td>
        <td class="name">Thompson</td>
        <td>North Little Rock</td>
        <td>Oman</td>
        <td class="email">nibh.Donec&#64;Aenean.edu</td>
        <td>Oct 21, 2012</td>
        <td>63825</td>
      </tr>
      <tr>
        <td>Kenyon</td>
        <td class="name">Good</td>
        <td>Port Arthur</td>
        <td>Thailand</td>
        <td class="email">libero.est.congue&#64;Duisrisus.org</td>
        <td>Sep 16, 2011</td>
        <td>33424</td>
      </tr>
      <tr>
        <td>Dominique</td>
        <td class="name">Gentry</td>
        <td>Clemson</td>
        <td>Turkey</td>
        <td class="email">est.mauris&#64;Craslorem.org</td>
        <td>Nov 16, 2011</td>
        <td>52636</td>
      </tr>
      <tr>
        <td>Rachel</td>
        <td class="name">Robinson</td>
        <td>Hastings</td>
        <td>Iran, Islamic Republic of</td>
        <td class="email">dolor.Quisque&#64;urnanec.edu</td>
        <td>Dec 20, 2011</td>
        <td>68943</td>
      </tr>
      <tr>
        <td>Beau</td>
        <td class="name">Murray</td>
        <td>Aguadilla</td>
        <td>Slovenia</td>
        <td class="email">ipsum.Suspendisse&#64;accumsansedfacilisis.ca</td>
        <td>Jun 23, 2011</td>
        <td>64758</td>
      </tr>
      <tr>
        <td>Fay</td>
        <td class="name">Coffey</td>
        <td>Waterloo</td>
        <td>Liberia</td>
        <td class="email">sed&#64;anteNunc.org</td>
        <td>Jun 29, 2011</td>
        <td>23261</td>
      </tr>
      <tr>
        <td>Anjolie</td>
        <td class="name">Hudson</td>
        <td>Villa Park</td>
        <td>Israel</td>
        <td class="email">Aliquam.erat.volutpat&#64;sedest.ca</td>
        <td>Sep 12, 2012</td>
        <td>61595</td>
      </tr>
      <tr>
        <td>Aurora</td>
        <td class="name">Wilcox</td>
        <td>Des Moines</td>
        <td>Belgium</td>
        <td class="email">lorem.tristique.aliquet&#64;mauris.ca</td>
        <td>Aug 1, 2011</td>
        <td>94743</td>
      </tr>
      <tr>
        <td>Graiden</td>
        <td class="name">Cantu</td>
        <td>Caguas</td>
        <td>French Guiana</td>
        <td class="email">dui.nec&#64;ornareInfaucibus.ca</td>
        <td>Aug 26, 2012</td>
        <td>47270</td>
      </tr>
      <tr>
        <td>Ifeoma</td>
        <td class="name">Snyder</td>
        <td>Stockton</td>
        <td>Grenada</td>
        <td class="email">pede&#64;duiSuspendisseac.edu</td>
        <td>Dec 21, 2012</td>
        <td>64082</td>
      </tr>
      <tr>
        <td>Fatima</td>
        <td class="name">Dillard</td>
        <td>Minot</td>
        <td>Malta</td>
        <td class="email">vitae&#64;risus.edu</td>
        <td>Jun 5, 2012</td>
        <td>22642</td>
      </tr>
      <tr>
        <td>Elvis</td>
        <td class="name">Hurst</td>
        <td>Fairfax</td>
        <td>Iraq</td>
        <td class="email">sem.ut.dolor&#64;Fuscemi.edu</td>
        <td>Jul 31, 2011</td>
        <td>49754</td>
      </tr>
      <tr>
        <td>Tyrone</td>
        <td class="name">Medina</td>
        <td>Fond du Lac</td>
        <td>Serbia and Montenegro</td>
        <td class="email">sapien.cursus.in&#64;Nunccommodo.com</td>
        <td>Sep 18, 2012</td>
        <td>71427</td>
      </tr>
      <tr>
        <td>Eleanor</td>
        <td class="name">Moran</td>
        <td>Ventura</td>
        <td>Switzerland</td>
        <td class="email">lorem&#64;dolor.org</td>
        <td>Jun 25, 2011</td>
        <td>37410</td>
      </tr>
      <tr>
        <td>Maris</td>
        <td class="name">Thomas</td>
        <td>Roswell</td>
        <td>Uganda</td>
        <td class="email">sagittis&#64;velmaurisInteger.edu</td>
        <td>Feb 1, 2012</td>
        <td>13281</td>
      </tr>
      <tr>
        <td>Herman</td>
        <td class="name">Webster</td>
        <td>Oak Ridge</td>
        <td>Peru</td>
        <td class="email">non.justo.Proin&#64;Class.com</td>
        <td>Jul 6, 2011</td>
        <td>64747</td>
      </tr>
      <tr>
        <td>Vladimir</td>
        <td class="name">Mccormick</td>
        <td>Durant</td>
        <td>Taiwan, Province of China</td>
        <td class="email">orci.in&#64;montes.ca</td>
        <td>Feb 6, 2011</td>
        <td>74553</td>
      </tr>
</body>
</html>