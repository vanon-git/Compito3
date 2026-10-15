<?php
error_reporting(E_ALL);

// RICHIAMA CONNESSIONE
require_once "connessione.php";

echo "<h3>Creazione e Popolazione Compito3</h3>";



// Creazione tabella Utenti
$sqlQuery = "CREATE TABLE IF NOT EXISTS " . TBL_UTENTI . " (
    userId INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL,
    totaleAcquisti FLOAT,
    PRIMARY KEY (userId)
)";
if (mysqli_query($mysqliConnection, $sqlQuery)) {
    echo "Tabella utenti creata...<br>";
}
// --- POPOLAMENTO ---

$sql = "INSERT INTO " . TBL_UTENTI . " (nome, cognome, username, password, totaleAcquisti) 
        VALUES ('Mattia', 'Vanon', 'admin', '1234', 0)";
        if ($resultQ = mysqli_query($mysqliConnection, $sql))
            printf("Utente inserito correttamente <br />\n");
        else {
            printf("Errore inserimento utente <br />\n");
            exit();
        }

            
            //chiudiamo la connessione
            $mysqliConnection->close();
?>