$(document).ready(function() {
$('.id').keyup(function() {
		$.ajax({
			type: "POST",
			url: "modules/ajax.php",
			data: {type: "id", value: $('.id').val()},
			success: function(result) {
				$('.produktname').val(result);
			}
		});		
	});
});

/* ORIGINALSCRIPT VON http://xorox.io/jquery-und-ajax-tutorial-anhand-der-postleitzahl-automatisch-den-ort-bestimmen/
$(document).ready(function() {
$('.zip').keyup(function() {
		$.ajax({
			type: "POST",
			url: "modules/ajax.php",
			data: {type: "plz", value: $('.zip').val()},
			success: function(result) {
				$('.city').val(result);
			}
		});		
	});
});
