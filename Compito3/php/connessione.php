<?php
require_once "datiGenerali.php";

// 1. Spegniamo temporaneamente le eccezioni fatali di MySQL (introdotte in PHP 8.1+)
mysqli_report(MYSQLI_REPORT_OFF);

// 2. Ci colleghiamo al DBMS (Senza database)
$mysqliConnection = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($mysqliConnection->connect_errno) {
    die("Ci sono problemi con la connessione al Server: " . $mysqliConnection->connect_error);
}

// 3. Proviamo a selezionare il database. Se fallisce (perché non esiste), lo creiamo!
if (!mysqli_select_db($mysqliConnection, DB_NAME)) {
    $sqlCreateDb = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if (mysqli_query($mysqliConnection, $sqlCreateDb)) {
        // Ora che è creato, lo selezioniamo con successo
        mysqli_select_db($mysqliConnection, DB_NAME);
    } else {
        die("Impossibile creare il database: " . mysqli_error($mysqliConnection));
    }
}

// 4. Riaccendiamo le eccezioni per far funzionare bene gli errori nel resto del sito
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Fatto! Ora $mysqliConnection è pronto e puntato su Compito2
?>