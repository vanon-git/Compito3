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

    // Conta quanti nft con disponibilità limitata l'utente ha già acquistato negli Acquisti passati
    // Per farlo leggiamo Acquisti.xml, file di verità sugli acquisiti effettuati fino ad ora
    // Calcola storico acquisti UNA VOLTA SOLA 
    $acquistatiStorico = array();

    if (file_exists('acquisti.xml')) {
        $xmlStringAcquisti = "";
        foreach (file("acquisti.xml") as $node) {
            $xmlStringAcquisti .= trim($node);
        }
        $docAcquisti = new DOMDocument();
        if (!$docAcquisti->loadXML($xmlStringAcquisti)) {
            die("Errore durante il parsing");
        }
        
        //getElementsByTagName('Acquisti') restituisce una LISTA (array) che contiene TUTTI gli elementi <Acquisti> del documento
        $acquisti = $docAcquisti->getElementsByTagName('Acquisto');

        //print_r($Acquisti); Per esempio abbiamo una cosa del genere: $Acquisti = [Acquisto1, Acquisto2, Acquisto3] → length = 3   (3 elementi)
        //                                                                         ↑        ↑        ↑
        //                                                                      item(0)  item(1)  item(2)   

        $quantitaGiaAcquistata = 0;

        for ($i = 0; $i < $acquisti->length; $i++) {   // $i va da 0 a 3
            $Acquisto = $acquisti->item($i);  // Prima item(0), poi item(1), poi item(2)

            // Dentro questo Acquisto specifico, cerca <username>
            // (ce n'è solo UNO per Acquisto)
            $usernameAcquisto = $Acquisto->getElementsByTagName('username')->item(0)->textContent;
            // → Sempre item(0) perché c'è solo UN username per Acquisto
            
            // Controlla solo gli $acquisti dell'utente corrente
            if ($usernameAcquisto == $_SESSION['username']) {

                //getElementsByTagName('articolo') restituisce una LISTA (array) che contiene tutti gli elementi <articolo> di quello specifico Acquisto
                $articolo = $Acquisto->getElementsByTagName('articolo');
        
            // Ciclo FOR per scorrere tutti gli <articolo> dentro questo Acquisto
            for ($j = 0; $j < $articoli->length; $j++) {
                $articolo = $articoli->item($j);

                $nomeNftAcquisto = $articolo->getElementsByTagName('nftId')->item(0)->textContent;
                $qta = $articolo->getElementsByTagName('quantita')->item(0)->textContent;

                // Controlla se nell'array $acquistatiStorico esiste già questo nftId
                // Se NON esiste (!isset), lo inizializziamo a 0
                if (!isset($acquistatiStorico[$nomeNftAcquisto])) {
                    $acquistatiStorico[$nomeNftAcquisto] = 0;
                }

                // Aggiungi la quantità di questo NFT al totale storico
                $acquistatiStorico[$nomeNftAcquisto] += $qta;
            }
        }
    }
}

    // Verifichiamo se il file xml che vogliamo caricare esiste
    if (!file_exists('data.xml')) {
        die("Errore: file data.xml non trovato");
    }

    // Costruiamo una stringa con il contenuto del file
    $xmlString = "";
    foreach ( file("data.xml") as $node ) {
	    $xmlString .= trim($node);
    }

    // Creazione del documento da usare
    $doc = new DOMDocument();

    // Carica contentuo del file (appiattito), nel documento $doc con DOM
    if (!$doc->loadXML($xmlString)) {
        die("Errore durante il parsing");
    }

    // Validazione DTD
    if ($doc->validate()) {
        $validDTD = "Il file XML data.xml è valido";
    } else  {
        $validDTD = "Il file XML data.xml non è valido";
    }


    // $NFTs è la lista degli elementi figli di catalogo del documento XML data.xml
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
                        <a href="carrello.php">Vai al carrello (<?php echo count($_SESSION['carrello']); ?> NFTs)</a> oppure
                        <a href="riepilogo_xml.php">Vai al pagamento (<?php echo count($_SESSION['carrello']); ?> NFTs)</a>
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
                <!-- Info DTD -->
                <div class="etichetta-sidebar">Modalità XML/DOM Validazione DTD</div>
                <div class="valore-sidebar" style="padding: 15px; background: #ffffff; border-radius: 10px; border-left: 3px solid white;">
                    <?php echo $validDTD ?> 
                </div>
            </div>
            
            <!-- griglia con i prodotti -->
            <div class="container-nft">
                <?php
                for($i=0; $i<$NFTs->length; $i++){
                    /* i-esimo NFT del documento, questo ha quattro sottoelementi: 
                    nome, prezzo, descrizione, caratteristiche */
                    $NFT = $NFTs->item($i);
                    
                    // Lettura attributi DTD dell'i-esimo NFT
                    $idNFT = $NFT->getAttribute('id'); //otteniamo attributo con metodo getAttribute
                    $categoria = $NFT->getAttribute('categoria');
                    $disponibilita = $NFT->getAttribute('disponibilita');

                    

                    // Scorriamo l'albero ed estraiamo/leggiamo i sottoelementi (di interesse) figli dell'i-esimo NFT
                    $nftId = $NFT->firstChild;
                    $nftIdValue = $nftId->textContent;
                    $prezzo = $nftId->nextSibling;   // nextSibling ci porta al prossimo sottoelemento
                    $prezzoValue = $prezzo->textContent;
                    $descrizione = $prezzo->nextSibling;
                    $descrizioneValue = $descrizione->textContent;

                    // Usa immagine dall'array definito sopra se disponibile
                    if(isset($immagini[$nftIdValue])){
                        $immagineFinale = $immagini[$nftIdValue];
                    }else{
                        $immagineFinale = "bianco.jpg";
                    }

                    
                    // Badge disponibilità
                    $coloriDisponibilita = array(
                        "limitato" => "#f39c12",
                        "esaurito" => "#e74c3c"
                    );

                    if(isset($coloriDisponibilita[$disponibilita])){
                        $badgeColor = $coloriDisponibilita[$disponibilita];
                    }else{
                        $badgeColor = "#95a5a6";  // default se non esiste
                    }
                ?>       
                    <div class="scheda-nft">
                        <img src="<?php echo $immagineFinale; ?>" alt="<?php echo $nftIdValue; ?>"/>
                        <div class="contenuto-scheda">
                            <div class="titolo-scheda"> <?php echo $nftIdValue; ?> </div>

                            <!-- Riga id, categoria e disponibilità (sotto il titolo) -->
                            <div class="info-scheda">
                                <span class="info"> <?php echo $idNFT; ?> </span>
                                <span class="info"> <i class="fas fa-tag"></i> <?php echo $categoria; ?> </span>
                                <span class="info" style="background: <?php echo $badgeColor; ?>; color: white;">
                                    <?php echo $disponibilita; ?>
                                </span>
                            </div>
                            
                            <!-- Descrizione -->
                            <div class="descrizione-scheda"> <?php echo $descrizioneValue; ?> </div>

                        
                            <div class="righa-carrello">
                            <span class="prezzo-scheda"><?php echo $prezzoValue; ?> &euro;</span>

                            <?php if ($disponibilita == 'esaurito'){  ?>
                                    <!-- Nft esaurito: input disabilitato SENZA form -->
                                    <input type="submit" disabled value="Esaurito" class="bottone-esaurito"/>

                            <?php }elseif($disponibilita == 'limitato'){ ?>
                                    <!-- LIMITATO: conta quanti ne ha già nel carrello -->
                                    <?php
                                    // 1. Conta quanti ne hai nel carrello
                                    $quantitaNelCarrello = 0;
                                    if (isset($_SESSION['carrello'])) {
                                        foreach ($_SESSION['carrello'] as $indice => $nftId) {
                                            if ($nftId == $nftIdValue) {
                                                $quantitaNelCarrello++;
                                            }
                                        }
                                    }

                                    // 2. Leggi dallo storico pre-calcolato a inizio script
                                    $quantitaGiaAcquistata = isset($acquistatiStorico[$nftIdValue]) ? $acquistatiStorico[$nftIdValue] : 0;

                                    // 3. Calcola totale
                                    $limiteMassimo = 2;
                                    $quantitaTotale = $quantitaNelCarrello + $quantitaGiaAcquistata;

                                    // 4. Mostra bottone appropriato
                                    if ($quantitaTotale >= $limiteMassimo) { ?>
                                        <!-- Ha già raggiunto il limite -->
                                        <input type="submit" disabled value="Limite raggiunto (<?php echo $quantitaTotale; ?>/<?php echo $limiteMassimo; ?>)" class="bottone-limitato"/>
                                    <?php } else { ?>
                                        <!-- Può ancora aggiungere -->
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="selection" value="<?php echo $nftIdValue; ?>"/>
                                            <input type="submit" name="aggiungiAlCarrello" class="bottone-aggiungi" value="Aggiungi (<?php echo $quantitaTotale; ?>/<?php echo $limiteMassimo; ?>)"/>
                                        </form>
                                    <?php } ?>

                            <?php }else{  ?>
                                    <!-- NFT disponibile: input normale -->
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                        <input type="hidden" name="selection" value="<?php echo $nftIdValue; ?>"/>
                                        <input type="submit" name="aggiungiAlCarrello" class="bottone-aggiungi" value="Aggiungi al carrello"/>
                                    </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
    <?php   } ?>  
        </div>
    </div>
</body>
</html>

