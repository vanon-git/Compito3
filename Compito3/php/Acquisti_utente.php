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

    
    $validDTD = "";

    // Inizializza un array vuoto che conterrà tutti gli Acquisti dell'utente corrente
    // Ogni elemento sarà un array associativo con: id, date, totale, articoli[]
    $userAcquisti = array();

    // Verifichiamo l'esistenza del file xml
    if (file_exists('acquisti.xml')) {
        $xmlString = "";
        foreach (file("acquisti.xml") as $node) {
            $xmlString .= trim($node);
        }

        $doc = new DOMDocument();
        if (!$doc->loadXML($xmlString)) {
            die("Errore durante il parsing");
        }

        // Validazione DTD
        if ($doc->validate()) {
            $validDTD = "Il file XML acquisti.xml è valido";
        } else {
            $validDTD = "Il file XML acquisti.xml non è valido";
        }

        // $tuttiAcquisti è la lista degli elementi figli di acquisti del documento XML acquisti.xml
        $tuttiAcquisti = $doc->documentElement->childNodes;

        // Seleziona e legge gli acquisti dell'utente corrente con DOM 
        for ($i = 0; $i < $tuttiAcquisti->length; $i++) {
            $acquisto = $tuttiAcquisti->item($i);

            // Legge attributo id
            $idAcquisto = $acquisto->getAttribute('id');

            // Navigazione DOM: primo figlio è <username>
            $username = $acquisto->firstChild;
            $usernameValue = $username->textContent;

            // Se non è dell'utente corrente(loggato), salta
            if ($usernameValue == $_SESSION['username']){
                // È un acquisto dell'utente corrente: accediamo a i dati e leggiamoli
                $date = $username->nextSibling;
                $dateValue = $date->textContent;

                $totale = $date->nextSibling;
                $totaleValue = $totale->textContent;

                $articoli = $totale->nextSibling; // <articoli>

                // Leggi articoli
                $articoliArray = array();
                $tuttiArticoli = $articoli->childNodes;

                for ($j = 0; $j < $tuttiArticoli->length; $j++) {
                    $articolo = $tuttiArticoli->item($j);

                    // Navighiamo dentro <articolo>
                    $NFT = $articolo->firstChild;
                    $NftValue = $NFT->textContent;

                    $prezzo = $NFT->nextSibling;
                    $prezzoValue = $prezzo->textContent;

                    $quantita = $prezzo->nextSibling;
                    $quantitaValue = $quantita->textContent;

                    $articoliArray[] = array(
                        'nft' => $NftValue,
                        'prezzo' => $prezzoValue,
                        'quantita' => $quantitaValue,
                        'subtotale' => $prezzoValue * $quantitaValue
                    );
                }

                // Aggiunge l'acquisto all'array, che serve per tenere tutte le informazioni di questo acquisto fatto dall'utente corrente
                $userAcquisti[] = array(
                    'id' => $idAcquisto,
                    'date' => $dateValue,
                    'totale' => $totaleValue,
                    'articoli' => $articoliArray
                );
            }
        // Alla fine di questo blocco, grazie al for, andiamo a cercare altri acquisti che l'utente corrente potrebbe aver fatto in acquisti.xml
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
    
    <!-- Sidebar profilo -->
    <div class="sidebar">
        <h2>Il tuo profilo</h2>
        <div class="etichetta-sidebar">Username:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['username']; ?></div>
        
        <div class="etichetta-sidebar">Nome:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['nome']; ?></div>
        
        <div class="etichetta-sidebar">Cognome:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['cognome']; ?></div>
        
        <div class="etichetta-sidebar">Finora hai speso:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['totaleAcquisti']; ?> &euro;</div>
        
        <div class="etichetta-sidebar">Ti sei collegato alle:</div>
        <div class="valore-sidebar"><?php echo date('g:i a', $_SESSION['dataLogin']); ?></div>

        <?php if ($validDTD){ ?>
            <!-- Info DTD validazione -->
            <div class="etichetta-sidebar">Modalità XML/DOM Validazione DTD</div>
            <div class="valore-sidebar" style="padding: 15px; background: #fffff; border-radius: 8px; border-left: 3px solid white;">
                <?php echo $validDTD; ?>
            </div>
        <?php } ?>
    </div>
    
    <!-- Container Acquisti -->
    <div class="container-ordini"> 
        <h2 class="titolo-ordini">
            <i class="fas fa-receipt"></i> I Miei Acquisti
        </h2>
        
        <?php if (empty($userAcquisti)){ ?>
                <div class="ordini-vuoto">
                    <p>Non hai ancora effettuato acquisti</p>
                    <a href="shop_xml.php">Vai al Catalogo</a>
                </div>
        <?php }else{ ?>
                <?php foreach ($userAcquisti as $key => $acquisto){ ?>
                    <div class="ordini-pieno">
                        <!-- Header acquisto -->
                        <div class="header-ordine">
                            <div class="contenuto-header">
                                <div>
                                    <h3 style="color: #2ec4b6; margin: 0 0 5px 0;">
                                        <i class="fas fa-shopping-bag"></i> Acquisto #<?php echo $acquisto['id']; ?>
                                    </h3>
                                    <p style="color: #a3a6ad;">
                                        <i class="fas fa-calendar-alt"></i> <?php echo $acquisto['date']; ?>
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 12px; color: #a3a6ad;">Totale ordine</div>
                                    <div style="font-size: 24px; color: #2ec4b6; font-weight: bold;">
                                        <?php echo $acquisto['totale']; ?> &euro;
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lista articoli dell'acquisto -->
                        <?php foreach ($acquisto['articoli'] as $key => $articolo){ ?>
                            <div class="articolo-ordine">
                                <div>
                                    <div class="nome-articolo">
                                        <?php echo $articolo['nft']; ?>
                                    </div>
                                    <div style="color: #a3a6ad; font-size: 0.9em;">
                                        <?php echo $articolo['quantita']; ?> x <?php echo $articolo['prezzo']; ?> &euro; = 
                                        <strong style="color: #2ec4b6;"><?php echo $articolo['subtotale']; ?> &euro;</strong>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            
                <!-- Totale speso dall'utente -->
                <div class="totale">
                    <div class="text-totale">Totale speso in tutti gli acquisti:</div>
                    <div class="price-totale"> <?php echo $_SESSION['totaleAcquisti']; ?> &euro; </div>
                </div>

                <div class="stile-bottone">
                    <a href="shop_xml.php" class="bottone-back"> <i class="fas fa-arrow-left"></i> Torna al Catalogo </a>
                </div>
       <?php } ?> 
    </div>
</div>
</body>
</html>

