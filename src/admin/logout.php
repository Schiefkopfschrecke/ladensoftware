<?php

// Logout

session_name('meine-cms-session');

session_start();

session_regenerate_id();

// In der DB vermerken, dass sich der Benutzer abgemeldet hat

include 'inc/functions.php';

db_anfragen('UPDATE mitglieder SET ist_angemeldet = 0 WHERE id = ' . $_SESSION['user']['id']);

// Session leeren

$_SESSION = array();

// Session zerstören (mutwillig)

session_destroy();

// Zum Login weiterleiten

header('Location:index.php');
