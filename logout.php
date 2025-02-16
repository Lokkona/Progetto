<?php
session_start(); // Avvia la sessione se non è già avviata

// Distrugge tutte le variabili di sessione
session_unset();
session_destroy();

// Reindirizza l'utente alla pagina di login
header("Location: Login.html");
exit();
?>