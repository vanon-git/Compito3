<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // se non ho il permesso, mi rimanda al login
    if (!isset($_SESSION['accessoPermesso'])){
        header('Location: login.php');
        exit();
    }

    // collego il database
    require_once("./connessione.php");

    // includo lo stile
    require_once("./stile_shop.php");
    
    // preparo le variabili che userò dopo
    $messaggio = "";
    $pagamentoOk = false;
    $mostraConferma = false;

    // controllo se c'è qualcosa da pagare
    // se ho roba nel carrello ma il totale non è settato, vuol dire che ho saltato il riepilogo
    if (!isset($_SESSION['daPagare']) || $_SESSION['daPagare'] == 0 || empty($_SESSION['carrello'])) {
        $messaggio = "Il carrello è vuoto, oppure stai saltando passaggi devi passare per il riepilogo dell'ordine per completare il pagamento!";
    } else {
        // C'è qualcosa da pagare

        // Se NON ha ancora confermato, mostra la conferma
        if (!isset($_POST['confermaPagamento']) ) {
            // Mostra schermata conferma
            $mostraConferma = true;
        } else {
            // Confermato, salva ordine in XML

            // Salviamo l'importo pagato ora (prima di azzerarlo)
            $importoPagatoOra = $_SESSION['daPagare'];

            // Calcoliamo il nuovo totale
            // Somma quanto pagato ora + quanto speso in passato
            $nuovoTotale = $_SESSION['daPagare'] + $_SESSION['spesaFinora'];
            
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


            // SCRITTURA ORDINE IN acquisti.xml

            // Carica o crea acquisti.xml
            if (file_exists('acquisti.xml')) {
                $xmlStringAcquisti = "";
                foreach (file("acquisti.xml") as $node) {
                    $xmlStringAcquisti .= trim($node);
                }
                $docAcquisti = new DOMDocument();
                if (!$docAcquisti->loadXML($xmlStringAcquisti)) {
                    die("Errore durante il parsing");
                }
                $acquisti = $docAcquisti->documentElement;
            } else {
                // Crea documento
                // DOMImplementation() crea un'istanza della classe DOMImplementation, necessaria per gestire funzionalità avanzate
                // dei documenti XML come la dichiarazione DOCTYPE
                $implementation = new DOMImplementation();
                // Crea il tipo di documento (DOCTYPE) specificando: nome root ('acquisti'), public ID (vuoto), system ID ('acquisti.dtd')
                $dtd=$implementation->createDocumentType('acquisti','','acquisti.dtd');
                // Crea il documento XML con: namespace URI (vuoto), nome elemento root (vuoto, verrà aggiunto dopo), e il DOCTYPE
                $docAcquisti=$implementation->createDocument('','',$dtd);
                // Imposta la codifica del documento su UTF-8
                $docAcquisti->encoding ='UTF-8';

                // Crea radice <acquisti>
                $acquisti=$docAcquisti->createElement('acquisti');
                $docAcquisti->appendChild($acquisti);
            }

            // Vogliamo output leggibile (indentato)
            $docAcquisti->formatOutput = true;
            
            // Crea nuovo elemento <acquisto>
            $nuovoAcquisto = $docAcquisti->createElement('acquisto');
            $nuovoAcquisto->setAttribute('id', rand(0,10000)); // Attributo id obbligatorio
            
            // Elementi figli di <acquisto>
            // <username>
            $elemUsername = $docAcquisti->createElement('username', $_SESSION['username']);
            $nuovoAcquisto->appendChild($elemUsername);

            // <date>
            $elemDate = $docAcquisti->createElement('date', date('d-m-Y H:i:s'));
            $nuovoAcquisto->appendChild($elemDate);

            // <totale>
            $elemTotale = $docAcquisti->createElement('totale', $importoPagatoOra);
            $nuovoAcquisto->appendChild($elemTotale);

            // <acquisti> (contenitore di sotto-elementi <articolo>)
            $elemArticoli = $docAcquisti->createElement('articoli');

            // Vogliamo salvare tutti gli <articolo> dell'ordine in <acquisti>, che a sua volta è un sotto-elemento di <ordine>
            // (vedere acquisti.dtd!!)
            // Conta le occorrenze di ogni NFT nel carrello
            $conteggio = array_count_values($_SESSION['carrello']);
            
            foreach ($conteggio as $nomeNFT => $quantita) {
                // Recupera prezzo (attraversando DOM catalogo data.xml)
                $prezzoTrovato = 0;

                // Navigazione nel catalogo
                for ($i = 0; $i < $NFTs->length; $i++) {
                    $NFT = $NFTs->item($i);
                    
                    $nftId = $biglietto->firstChild;
                    $nftIdValue = $nftId->textContent;
                    if ($nftIdValue == $nomeNFT) {
                        $prezzo = $nome->nextSibling;
                        $prezzoTrovato = $prezzo->textContent;
                        // Il break serve per uscire dal ciclo for non appena viene trovato l’elemento desiderato, 
                        // evitando di continuare a controllare gli altri
                        break;
                    }
                }   

                // Crea elemento <articolo>
                $elemArticolo = $docAcquisti->createElement('articolo');

                // Figli di <articolo>: <NFT>, <prezzo>, <quantita>
                $elemNFT = $docAcquisti->createElement('nftId', $nomeNFT);
                $elemPrezzo = $docAcquisti->createElement('prezzo', $prezzoTrovato);
                $elemQuantita = $docAcquisti->createElement('quantita', $quantita);

                $elemArticolo->appendChild($elemNFT);
                $elemArticolo->appendChild($elemPrezzo);
                $elemArticolo->appendChild($elemQuantita);
                

                // Aggiungi articolo alla lista articoli
                $elemArticoli->appendChild($elemArticolo);
            }
            
            $nuovoAcquisto->appendChild($elemArticoli);

            $primoAcquisto = $acquisto->firstChild;

            // Aggiungi ordine alla radice <acquisto>, ma come nuovo primo figlio
            $acquisti->insertBefore($nuovoAcquisto,$primoAcquisto);
            
                
            // Salva su file
            $filename="acquisti.xml";
            if ($docAcquisti->save($filename)){
                // QUERY UPDATE: aggiorna la spesa dell'utente
                $sql = "UPDATE $Utenti_table_name 
                        SET sommeSpese = \"$nuovoTotale\" 
                        WHERE username = \"{$_SESSION['username']}\"";
                
                // Eseguiamo la query e la controlliamo
                if (!mysqli_query($mysqliConnection, $sql) || mysqli_affected_rows($mysqliConnection) != 1) {
                    printf("Errore nella gestione del pagamento: %s\n", mysqli_error($mysqliConnection));
                    // UPDATE fallito: elimina l'acquisto appena salvato nell'XML 
                    $acquisti->removeChild($nuovoAcquisto);
                    $docAcquisti->save($filename);
                    $messaggio = "Errore nell'aggiornamento della spesa totale.";
                }else{
                    // SUCCESSO
                    $pagamentoOk = true;

                    // Aggiorna la sessione con il nuovo totale
                    $_SESSION['spesaFinora'] = $nuovoTotale;
                    
                    // Svuotiamo il carrello
                    $_SESSION['carrello'] = array();
                    $_SESSION['daPagare'] = 0;
                    
                    $messaggio = "il pagamento è stato completato con successo";
                }
            }else{
                $messaggio = "Si è verificato un problema durante il salvataggio dell'ordine.";
            }
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Pagamento - Sappy Seals Shop</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <?php echo $stile_shop; ?>
    </head>
    <body>
        <?php require("menu_shop.php"); ?>
        
        <div class="container-principale">
            <!-- colonna laterale con il profilo -->
            <div class="sidebar">
                <h2>Il tuo profilo</h2>
                <div class="etichetta-sidebar">Username:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['username']; ?></div>
                
                <div class="etichetta-sidebar">Nome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['nome']; ?></div>
                
                <div class="etichetta-sidebar">Cognome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['cognome']; ?></div>
                
                <div class="etichetta-sidebar">Totale speso:</div>
                <div class="valore-sidebar">
                    <?php echo $_SESSION['totaleAcquisti']; ?> &euro;
                </div>
                
                <div class="etichetta-sidebar">Ti sei collegato alle:</div>
                <div class="valore-sidebar">
                    <?php echo date ('g:i a', $_SESSION['dataLogin']) ?>
                </div>
            </div>
            
            <!-- blocco centrale del pagamento -->
            <div class="container-pagamento">
                <?php 
                    // se il pagamento è andato bene mostro la schermata verde
                    if ($pagamentoOk){
                ?>
                        <div class="container-successo">
                            <div class="icona-successo">
                                <i class="fas fa-check"></i>
                            </div>
                            <h2 class="titolo-successo">Pagamento Effettuato!</h2>
                            <p class="testo-successo">
                                Gentile <?php echo $_SESSION['nome']; ?>, <?php echo $messaggio; ?>.
                            </p>
                            <div class="dettaglio-spesa">
                                <div class="importo-ora">
                                    <span>Importo pagato ora:</span>
                                    <strong><?php echo $importoPagatoOra; ?> &euro;</strong>
                                </div>
                                <div class="spesa-totale">
                                    <span>Spesa totale:</span>
                                    <strong><?php echo $_SESSION['totaleAcquisti']; ?> &euro;</strong>
                                </div>
                            </div>
                        </div>
                        <div class="box-info">
                            <div class="contenuto-info">
                                <div style="color: #a3a6ad; font-size: 20px;">Per vedere la cronologia degli Acquisti: </div>
                                <a href="ordini_utente.php" class="bottone"><i class="fas fa-box"></i> I Miei Acquisti </a>
                            </div>
                        </div>
                        <div class="container-azioni">
                            <a href="shop_xml.php" class="bottone-back">Torna al Catalogo</a>
                            <a href="logout.php" class="bottone">Esci</a>
                        </div>
            <?php }else{

                        if ($mostraConferma){ ?>
                            <!-- Schermata conferma -->
                            <div class="container-conferma">
                                <div class="icona-allerta"> 
                                    <i class="fas fa-exclamation-triangle"></i> 
                                </div>
                                <h2 class="titolo-conferma"> Conferma Pagamento </h2>
                                <p class="testo-conferma">Stai per procedere con il pagamento di:</p>
                                <div class="importo-conferma"><?php echo $_SESSION['daPagare']; ?> &euro; </div>
                                
                                <!-- Bottoni -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                                    <div class="container-azioni">
                                        <a href="riepilogo_xml.php" class="bottone-back"> Annulla </a>
                                        <input type="submit" name="confermaPagamento" value="Conferma e Paga" class="bottone" />
                                    </div>
                                </form>
                            </div>
                <?php }else{ ?>
                            <!-- ERRORE -->
                            <div class="container-errore">
                                <div class="icona-errore">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <h2 class="titolo-errore">Attenzione</h2>
                                <p class="testo-errore"><?php echo $messaggio; ?></p>
                            </div>
                            <div class="container-azioni">
                                <a href="shop_xml.php" class="bottone-back">Vai al Catalogo</a>
                                <a href="riepilogo_xml.php" class="bottone">Vai al Riepilogo/Pagamento</a>
                            </div>
                <?php } ?>
            <?php } ?>
            </div>
        </div>

        </body>
        </html>

