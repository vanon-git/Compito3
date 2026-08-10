<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // controllo se l'utente è loggato, altrimenti lo rimando al login
    if (!isset($_SESSION['accessoPermesso']))
        header('Location: login.php');

    // collego il database
    require_once("./connessione.php");

    // includo il file con lo stile css
    require_once("./stile_shop.php");
    
    // query per prendere i prodotti e le relative immagini (usando LEFT JOIN per non perdere prodotti senza foto)
    $sql="SELECT n.nome, n.costoNft, i.percorsofile FROM " . TBL_NFT . " AS n LEFT JOIN " . TBL_IMMAGINI_NFT . " AS i ON n.nftId = i.nftId";

    // eseguo la query e controllo errori
    if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
        printf("Si è verifiacto un errore nella selezione.\n");
        exit();
    }
    
    // chiudo la connessione (i risultati rimangono in memoria in $resultQ)
    $mysqliConnection->close();
?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Shop - Sappy Seals Shop</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <?php echo $stile_shop; ?>
    </head>
    <body>
        <?php
            require("menu_shop.php");
        ?>

        <?php
            // se il carrello non esiste ancora, lo creo vuoto
            if((!isset($_SESSION['carrello']) && !$_POST)) {
                $_SESSION['carrello'] = array();
            } else {
                // se l'utente ha cliccato su "aggiungi", metto il prodotto nel carrello
                if(isset($_POST['selection'])) {
                    $_SESSION['carrello'][] = $_POST['selection'];
        ?>  
                    <!-- mostro il messaggio di conferma solo quando aggiungo qualcosa --> 
                    <div class="messaggio-aggiunto">
                        <p><strong><i class="fas fa-circle-check"></i> Aggiunto al carrello:</strong> <?php echo $_POST['selection']; ?></p>
                        <a href="carrello.php">Vai al carrello (<?php echo count($_SESSION['carrello']); ?> articoli)</a> oppure
                        <a href="riepilogo.php">Vai al pagamento (<?php echo count($_SESSION['carrello']); ?> articoli)</a>
                    </div>
                    <?php
                }
            ?>
                <?php   
            }
        ?>

        <div class="container-principale">
            <!-- colonna laterale con i dati profilo -->
            <div class="sidebar">
                <h2>Il tuo profilo</h2>
                <div class="etichetta-sidebar">Username:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['username']; ?></div>
                
                <div class="etichetta-sidebar">Nome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['nome']; ?></div>
                
                <div class="etichetta-sidebar">Cognome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['cognome']; ?></div>
                
                <div class="etichetta-sidebar">Finora hai speso:</div>
                <div class="valore-sidebar"> <?php echo $_SESSION['totaleAcquisti']; ?> &euro; </div>
                
                <div class="etichetta-sidebar">Ti sei collegato alle:</div>
                <div class="valore-sidebar">
                    <?php echo date ('g:i a', $_SESSION['dataLogin']) ?>
                </div>
            </div>
            
            <!-- griglia con i prodotti -->
            <div class="container-meraviglie">
                <?php
                    // scorro tutti i risultati trovati nel database
            while ($row = mysqli_fetch_array($resultQ)){
                                    
                $nome = $row['nome'];
                $prezzo = $row['costoNft'];

                // controllo se il database mi ha dato un percorso immagine valido
                if (isset($row['percorsofile']) && !empty($row['percorsofile'])) {
                    $immagine = $row['percorsofile'];
                } else {
                    // se non c'è immagine nel db, controllo se c'è nell'array vecchio o uso quella di default
                    if(isset($immagini[$nome])) {
                        $immagine = $immagini[$nome];
                    } else {
                        $immagine = "bianco.jpg";   // immagine vuota standard
                    }
                }
                ?>      
                        <!-- scheda del singolo prodotto -->
                        <div class="scheda-meraviglia">
                            <img src="<?php echo $immagine; ?>" alt="<?php echo htmlspecialchars($nome); ?>" />
                            <div class="contenuto-scheda">
                                <div class="titolo-scheda"><?php echo $nome; ?></div>
                                <div class="righa-carrello">
                                    <span class="prezzo-scheda"><?php echo $prezzo; ?> &euro;</span>
                                    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                                        <!-- invio il nome del prodotto via POST per aggiungerlo al carrello -->
                                        <input type="hidden" name="selection" value="<?php echo $nome; ?>" />
                                        <input type="submit" name="aggiungiAlCarrello" class="bottone-aggiungi" value="Aggiungi al carrello" />
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                    } 
                ?>
            </div>  
        </div>
    </body>
</html>
