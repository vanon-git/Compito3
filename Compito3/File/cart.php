<?php
session_name("SessioneUtente");
session_start();
if (!isset($_SESSION['accessoPermesso'])) {
       header('Location: ../php/login.php');    
}
else{?>

<?xml version = "1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//IT"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
       

<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
    
       <head>
            <title>Homework1-1/3</title>
            <link rel="stylesheet" href="../css/Style.css?ts=<?=time()?>&quot"" type="text/css" />
       </head>

       <body>
              
              <div class="header">
                     <ul>
                     <h1>Sappy Seals NFT</h1>
                     <li class="menu-link-a"><a href="../php/login.php"><Strong>Login</Strong></a></li>
                     <li class="menu-link-a"><a href="../php/logout.php"><Strong>Logout</Strong></a></li>
                     <li class="menu-link-a"><a href="../php/register.php"><Strong>Registrazione</Strong></a></li>
                     </ul>
              </div>
              <div  class="center">
                     <ul>
                     <li class="menu-link"><a href='../index.html'><strong>HOME</strong></a></li>
                     <li class="menu-link"><a href='../File/cart.php'><strong>Carrello</strong></a></li>
                     </ul>

<?php
}

$db_name = "pluto";
$utenti= "utenti";
$nft = "nft";
$gadget = "gadget";
    $mysqliConnection = new mysqli("localhost", "root", "",$db_name);
// Verifica connessione
if ($mysqliConnection->connect_error) {
    die("Connessione fallita: " . $mysqliConnection->connect_error);
}

// Funzione per aggiungere un prodotto al carrello
function addToCart($userId, $productId, $quantity) {
    global $mysqliConnection;

    // Ottieni il prezzo del prodotto
    $productStmt = $mysqliConnection->prepare("SELECT PrezzoNft FROM nft WHERE Id_Prodotto = ?");
    $productStmt->bind_param("i", $productId);
    $productStmt->execute();
    $productResult = $productStmt->get_result();

    if ($productResult->num_rows === 0) {
        echo "Prodotto non trovato!";
        $productStmt->close();
        return;
    }

    $productRow = $productResult->fetch_assoc();
    $price = $productRow['PrezzoNft'];

    $productStmt->close();

    // Controlla se il prodotto è già nel carrello
    $cartStmt = $mysqliConnection->prepare("SELECT id, quantita FROM carrello WHERE id_utente = ? AND id_prodotto = ?");
    $cartStmt->bind_param("ii", $userId, $productId);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();

    if ($cartResult->num_rows > 0) {
        // Aggiorna la quantità esistente
        $row = $cartResult->fetch_assoc();
        $newQuantity = $row['quantita'] + $quantity;
        $updateStmt = $mysqliConnection->prepare("UPDATE carrello SET quantita = ? WHERE id = ?");
        $updateStmt->bind_param("ii", $newQuantity, $row['id']);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // Inserisci un nuovo articolo
        $insertStmt = $mysqliConnection->prepare("INSERT INTO carrello (id_utente, id_prodotto, quantita, prezzo) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("iiid", $userId, $productId, $quantity, $price);
        $insertStmt->execute();
        $insertStmt->close();
    }

    $cartStmt->close();
    echo "Prodotto aggiunto al carrello!";
}

// Funzione per rimuovere un prodotto dal carrello
function removeFromCart($userId, $productId) {
    global $mysqliConnection;

    $stmt = $mysqliConnection->prepare("DELETE FROM carrello WHERE id_utente = ? AND id_prodotto  = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Prodotto rimosso dal carrello!";
    } else {
        echo "Il prodotto non era presente nel carrello.";
    }

    $stmt->close();
}

// Esempio di utilizzo
$userId = 1; // ID utente simulato
$productId = 2; // ID prodotto simulato
$quantity = 3; // Quantità da aggiungere

// Aggiungi prodotto al carrello
addToCart($userId, $productId, $quantity);

// Rimuovi prodotto dal carrello
removeFromCart($userId, $productId);

// Chiudi connessione
$mysqliConnection->close();
        


?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galleria Immagini</title>



</div>
              <div class="footer">

                     <p><strong><em>Created By : Mattia Vanon</em></strong></p>
                     
              </div>

       </body>

</html>

