<?php
error_reporting(E_ALL);

// RICHIAMA SOLO CONNESSIONE, COME VOLEVI TU!
require_once "connessione.php";

echo "<h3>Creazione e Popolazione Compito2</h3>";

// Facciamo pulizia delle tabelle vecchie per non sfasare mai le immagini
mysqli_query($mysqliConnection, "DROP TABLE IF EXISTS " . TBL_IMMAGINI_NFT);
mysqli_query($mysqliConnection, "DROP TABLE IF EXISTS " . TBL_NFT);
mysqli_query($mysqliConnection, "DROP TABLE IF EXISTS " . TBL_UTENTI);

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

// Creazione tabella NFT (Senza AUTO_INCREMENT per forzare gli ID 1,2,3,4,5,6 e allineare le foto)
$sqlQuery = "CREATE TABLE IF NOT EXISTS " . TBL_NFT . " (
    nftId INT NOT NULL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    costoNft FLOAT
)";
if (mysqli_query($mysqliConnection, $sqlQuery)) {
    echo "Tabella nft creata...<br>";
}

// Creazione tabella Immagini NFT
$sqlQuery = "CREATE TABLE IF NOT EXISTS " . TBL_IMMAGINI_NFT . " (
    imgId INT NOT NULL AUTO_INCREMENT,
    nftId INT NOT NULL,
    percorsofile VARCHAR(255) NOT NULL,
    PRIMARY KEY (imgId),
    FOREIGN KEY (nftId) REFERENCES " . TBL_NFT . "(nftId) ON DELETE CASCADE
)";
if (mysqli_query($mysqliConnection, $sqlQuery)) {
    echo "Tabella immagininft creata...<br>";
}

// --- POPOLAMENTO ---

$sql = "INSERT INTO " . TBL_UTENTI . " (nome, cognome, username, password, totaleAcquisti) 
        VALUES ('Mattia', 'Vanon', 'admin', '1234', 0)";
mysqli_query($mysqliConnection, $sql);

$sql = "INSERT INTO " . TBL_NFT . " (nftId, nome, costoNft) VALUES
        (1, 'SappySeals6589', 247.00),
        (2, 'SappySeals8569', 245.99),
        (3, 'SappySeals2546', 458.50),
        (4, 'SappySeals4598', 1478.99),
        (5, 'SappySeals1456', 85.50),
        (6, 'SappySeals4587', 412.99)";
mysqli_query($mysqliConnection, $sql);

$sqlImg = "INSERT INTO " . TBL_IMMAGINI_NFT . " (nftId, percorsofile) VALUES
          (1, '../imgCarr/imgcostosa1.png'),
          (2, '../imgCarr/imgcostosa5.png'),
          (3, '../imgCarr/imgcostosa9.png'),
          (4, '../imgCarr/imgrara3.jpg'),
          (5, '../imgCarr/imgrara7.jpg'),
          (6, '../imgCarr/imgrara8.jpg')";
mysqli_query($mysqliConnection, $sqlImg);

echo "<br><strong>Tutto completato perfettamente!</strong>";
$mysqliConnection->close();
?>