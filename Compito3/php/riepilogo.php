<?php
    session_start();

    // se non ho il permesso di accesso, rimando al login
    if (!isset($_SESSION['accessoPermesso']))
        header('Location: login.php');

    // collego il database e il file di stile
    require_once("./connessione.php");
    require_once("./stile_shop.php");

    // preparo le variabili per il totale e la lista degli NFT
    $_SESSION['daPagare'] = 0; 
    $ListaNft = array(); 

    // controllo se c'è qualcosa nel carrello
    if (isset($_SESSION['carrello']) && count($_SESSION['carrello']) > 0) {

        foreach ($_SESSION['carrello'] as $indice => $nome) {
            
            // pulisco il nome per sicurezza prima di usarlo nella query
            $nomeSafe = mysqli_real_escape_string($mysqliConnection, $nome);

            // cerco i dati dell'NFT e la sua immagine unendo le tabelle
            $sql = "SELECT n.nome, n.costoNft, i.percorsofile FROM " . TBL_NFT . " AS n LEFT JOIN " . TBL_IMMAGINI_NFT . " AS i ON n.nftId = i.nftId WHERE n.nome = '$nomeSafe' LIMIT 1";
            
            // eseguo la query
            if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
                printf("Errore query: %s\n", mysqli_error($mysqliConnection));
                exit();
            }
            
            // se trovo il prodotto
            if ($row = mysqli_fetch_array($resultQ)) {
                // aggiungo il prezzo al totale
                $_SESSION['daPagare'] += $row['costoNft'];
                
                // se c'è un'immagine la uso, altrimenti ne metto una bianca standard
                $imgTrovata = (!empty($row['percorsofile'])) ? $row['percorsofile'] : "bianco.jpg";
                
                // salvo i dati in un array per stamparli dopo nell'HTML
                $ListaNft[] = array(
                    "nome" => $row['nome'],
                    "prezzo" => $row['costoNft'],
                    "immagine" => $imgTrovata
                );
            }
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Riepilogo Acquisti - Sappy Seals Shop</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <?php echo $stile_shop; ?>
    </head>
    <body>
        <?php require("menu_shop.php"); ?>

        <div class="container-principale">
            
            <!-- colonna laterale con i dati utente -->
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

                <div class="etichetta-sidebar">Articoli nel carrello:</div>
                <div class="valore-sidebar">
                    <?php echo count($_SESSION['carrello']); ?>
                </div>
            </div>

            <!-- parte centrale con il riepilogo -->
            <div class="container-riepilogo">
                <h2 class="titolo-riepilogo">Riepilogo Acquisti</h2>

                <?php
                    // se il carrello è vuoto mostro il messaggio
                    if(empty($_SESSION['carrello'])){
                ?>  
                    <div class="riepilogo-vuoto">
                        <p>Il tuo carrello è vuoto</p>
                        <a href="shop.php">Vai al Catalogo</a>
                    </div>
                <?php 
                    // altrimenti mostro gli articoli
                    } else {
                ?>
                    <div class="riepilogo-pieno">
                        <?php
                            foreach ($ListaNft as $k => $SingoloNft){ 
                        ?>
                            <div class="articolo-riepilogo">
                                <img src="<?php echo $SingoloNft['immagine']; ?>" alt="<?php echo $SingoloNft['nome']; ?>" class="immagine-articolo" />
                                <div class="info-articolo">           
                                    <div class="nome-articolo"><?php echo $SingoloNft['nome']; ?></div>
                                </div>
                                <div class="prezzo-articolo"><?php echo $SingoloNft['prezzo']; ?> &euro; </div>
                            </div>
                        <?php 
                            }
                        ?>
                    </div>

                    <!-- zona totale e bottoni -->
                    <div class="container-prezzoTot">
                        <div class="titolo-totale">Totale da pagare:</div>
                        <div class="valore-totale"><?php echo $_SESSION['daPagare']; ?> &euro;</div>
                    </div>

                    <form action="pagamento.php" method="post">
                        <div class="container-bottoni">
                            <a href="shop.php" class="bottone-grey">Continua con gli acquisti</a>
                            <input type="submit" name="invioPagamento" value="Procedi con il pagamento" class="bottone-verde" />
                        </div>
                    </form>
                <?php 
                    }
                ?>
            </div>
        </div>
    </body>
</html>
