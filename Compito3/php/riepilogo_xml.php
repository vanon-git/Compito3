<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // Controllo accesso
    if (!isset($_SESSION['accessoPermesso'])) {
        header("Location: login.php");
        exit();
    }

    require_once('./stile_shop.php');

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
                        <a href="shop_xml.php">Vai al Catalogo</a>
                    </div>
                <?php 
                    // altrimenti mostro gli articoli
                    } else {
                ?>
                                <?php
                // Verifichiamo se il file xml esiste
                    if (!file_exists('data.xml')) {
                        die("Errore: file data.xml non trovato");
                    }

                    
                    // Costruiamo una stringa con il contenuto del file
                    $xmlString = "";
                    foreach ( file("data.xml") as $node ) {
                        $xmlString .= trim($node);
                    }

                    // Creazione del documento
                    $doc = new DOMDocument();

                    // Carica contentuo del file nel documento $doc con DOM
                    if (!$doc->loadXML($xmlString)) {
                        die("Errore durante il parsing");
                    }

                    // $NFTs è la lista degli elementi figli della radice catalogo del documento XML data.xml
                    $NFTs = $doc->documentElement->childNodes;


                    // Array immagini
                    $immagini = array(
                        "SappySeals_6589" => "../imgCarr/imgcostosa1.png",
                        "SappySeals_8569" => "../imgCarr/imgcostosa5.png",
                        "SappySeals_2546" => "../imgCarr/imgcostosa9.png",
                        "SappySeals_4598" => "../imgCarr/imgrara3.jpg",
                        "SappySeals_1456" => "../imgCarr/imgrara7.jpg",
                        "SappySeals_4587" => "../imgCarr/imgrara8.jpg",
                    );

                    
                    // Crea una mappa del tipo:  nftId → prezzo  dal DOM
                    $catalogoMap = array();

                    for($i=0; $i<$NFTs->length; $i++){
                        $NFT = $NFTs->item($i);

                        $nftId = $NFT->firstChild;
                        $nftIdValue = $nftId->textContent;
                        $prezzo = $nftId->nextSibling;   // nextSibling ci porta al prossimo sottoelemento
                        $prezzoValue = $prezzo->textContent;
                        
                        $catalogoMap[$nftIdValue] = $prezzoValue;
                    }

                    // Calcola il totale da pagare
                    $_SESSION['daPagare'] = 0;
                    // Raggruppa gli articoli uguali
                    $ListaNft = array(); // Array per raggruppare

                    // Conta le occorrenze di ogni NFT nel carrello
                    $conteggioCarrello = array_count_values($_SESSION['carrello']);

                    foreach ($conteggioCarrello as $nomeNFT => $quantita) {
                        if (isset($catalogoMap[$nomeNFT])) {
                            $prezzoUnitario = $catalogoMap[$nomeNFT];
                            $subtotale = $prezzoUnitario * $quantita;
                            
                            // Accumula totale generale
                            $_SESSION['daPagare'] += $subtotale;
                            
                            // Aggiungi all'array raggruppato
                            $ListaNft[] = array(
                                'nome' => $nomeNFT,
                                'prezzoUnitario' => $prezzoUnitario,
                                'quantita' => $quantita,
                                'subtotale' => $subtotale,
                                'immagine' => isset($immagini[$nomeNFT]) ? $immagini[$nomeNFT] : 'bianco.jpg'
                            );
                        }
                    }
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
                                <div class="prezzo-articolo"><?php echo $SingoloNft['prezzoUnitario']; ?> &euro; </div>
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

                    <form action="pagamento_xml.php" method="post">
                        <div class="container-bottoni">
                            <a href="shop_xml.php" class="bottone-grey">Continua con gli acquisti</a>
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
