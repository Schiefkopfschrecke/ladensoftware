<?php
error_reporting(E_ALL);

// include functions, configurations and header
include 'inc/functions.php';
include 'inc/login.php';
include 'inc/config.php';
include 'inc/header.php';
?>

<style type="text/css">
  th[data-sort] {
    cursor: pointer;
  }

  .disabled {
    opacity: 0.5;
  }
</style>
<p><b>Nutzungsbedingungen:</b> teile, sei sorgfältig, grosszügig und ehrlich, verzeihe :-). Der GSL übernimmt keine Verantwortung.</p>
<p align="right"><a href="meine_boerse.php">meine Gegenstände</a></p>
<p><input id='myInput' onkeyup='searchTable()' type='text' placeholder="Suche in Tabelle" autofocus></p>

<?
echo '<p><small>auf Tabellentitel klicken zum Sortieren</small></p><table><thead><tr><th data-sort="string">Kategorie:</th><th data-sort="string">Gegenstand:</th><th data-sort="string">Beschrieb:</th><th data-sort="string">Bedingungen:</th><th></th></tr></thead><tbody id="myTable">';
$sql = 'SELECT * FROM mitglieder WHERE id = ' . $_SESSION['user']['id'];
$res = db_anfragen($sql);
$mitglieddaten = mysqli_fetch_assoc($res);
$sql = 'SELECT * FROM gegenstaende ORDER BY kategorie DESC';
$res = db_anfragen($sql);
while ($gegenstaende = mysqli_fetch_assoc($res)) {
  echo '<tr><td>' . $gegenstaende['kategorie'] . '</td><td>' . $gegenstaende['bezeichnung'] . '</td><td>' . $gegenstaende['beschrieb'] . '</td><td>' . $gegenstaende['bedingungen'] . '</td><td><a href="gegenstand.php?id=' . $gegenstaende['id'] . '" title="Details">Details</a></td></tr>';
}
echo '</tbody></table>';
?>

<!-- Todo: E-Mail-Adresse anpassen -->
<p><small>Verbesserungsvorschläge an <a href="mailto:a.koenig@immerda.ch"><small>a.koenig@immerda.ch</small></a></small></p>
<script>
  function searchTable() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td");
      for (j = 0; j < td.length; j++) {
        if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
          found = true;
        }
      }
      if (found) {
        tr[i].style.display = "";
        found = false;
      } else {
        tr[i].style.display = "none";
      }
    }
  }
</script>
<!-- Anfang Tabellensortierungstool Studpid Table -->
<script src="js/stupid-table-plugin/stupidtable.js?dev"></script>
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

<?
include 'inc/footer.php';
?>