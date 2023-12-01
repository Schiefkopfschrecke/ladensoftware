<?php
session_name('meine-einkaufs-session');

session_start();

session_regenerate_id();

// Zeit bis zum »timeout« in Sekunden
$session_timeout = 300; // 1800 Sek./60 Sek. = 30 Minuten
if (!isset($_SESSION['last_visit'])) {
	$_SESSION['last_visit'] = time();
	// Aktion der Session wird ausgeführt
}
if ((time() - $_SESSION['last_visit']) > $session_timeout) {
	session_destroy();
	// Aktion der Session wird erneut ausgeführt
	header('Location:../index.php');
}

$_SESSION['last_visit'] = time();

/*echo $_SESSION['user']['pw'];
	$sql = 'SELECT id FROM mitglieder WHERE benutzername = ' . $_SESSION['user']['benutzername'] . ' AND pw = ' . $_SESSION['user']['pw'];
echo $sql;
	$res = db_anfragen($sql);
	$mitgliedid['id'] = mysqli_fetch_assoc($res);*/
$total = 0;



// Wenn keine Angaben in der Session sind, zum Login weiterleiten
if (!filter_var($_SESSION['user']['benutzername'])) {

	// Zum Login weiterleiten
	header('Location:login.php');
}
?>