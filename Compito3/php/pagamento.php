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

    // controllo se c'è qualcosa da pagare
    // se ho roba nel carrello ma il totale non è settato, vuol dire che ho saltato il riepilogo
    if (!isset($_SESSION['daPagare']) && !empty($_SESSION['carrello'])){
        $messaggio = "Devi passare per il riepilogo dell'ordine prima di concludere l'acquisto! (Premi su Vai al Pagamento)";
    } else {
        // se il totale è zero o non esiste
        if (!isset($_SESSION['daPagare']) || $_SESSION['daPagare'] == 0) {
            $messaggio = "Non c'è nulla da pagare. Il carrello è vuoto! (Premi su Vai al Catalogo)";
        } else {

            // controllo di sicurezza: verifico se arrivo davvero dal tasto "Procedi"
            if(isset($_POST['invioPagamento'])!="Procedi con il pagamento"){
                header('Location: riepilogo.php');
                exit();
            }

            // mi salvo quanto sto pagando adesso prima di resettare tutto
            $importoPagatoOra = $_SESSION['daPagare'];
        
            // calcolo il nuovo totale storico (quello vecchio + quello di oggi)
            $nuovoTotale = $_SESSION['daPagare'] + $_SESSION['totaleAcquisti'];
            
            // aggiorno il database con il nuovo totale speso dall'utente
            $sql = "UPDATE " . TBL_UTENTI . " SET totaleAcquisti = $nuovoTotale WHERE username = '$_SESSION[username]'";
            
            // eseguo l'aggiornamento
            if (!mysqli_query($mysqliConnection, $sql)) {
                printf("Errore nella gestione del pagamento: %s\n", mysqli_error($mysqliConnection));
                exit(); // blocco tutto se c'è un errore grave
            }
            
            // controllo se l'aggiornamento è andato a buon fine (1 riga modificata)
            if (mysqli_affected_rows($mysqliConnection) == 1) {
                $pagamentoOk = true;
                
                // aggiorno la sessione col nuovo totale storico
                $_SESSION['totaleAcquisti'] = $nuovoTotale;
                
                // svuoto il carrello e azzero il conto attuale
                $_SESSION['carrello'] = array();
                $_SESSION['daPagare'] = 0;
                
                $messaggio = "il pagamento è stato completato con successo";
            } else {
                $messaggio = "Si è verificato un problema durante il pagamento.";
            }
        }
    }

    $mysqliConnection->close();
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
                        <div class="container-azioni">
                            <a href="shop.php" class="bottone-back">Torna al Catalogo</a>
                            <a href="logout.php" class="bottone">Esci</a>
                        </div>
                        <?php
                    // altrimenti mostro l'errore o l'avviso
                    } else {
                ?>
                        <div class="container-errore">
                            <div class="icona-errore">
                                <i class="fas fa-times"></i>
                            </div>
                            <h2 class="titolo-errore">Attenzione</h2>
                            <p class="testo-errore"><?php echo $messaggio; ?></p>
                        </div>
                        
                        <div class="container-azioni">
                            <a href="shop.php" class="bottone-back">Vai al Catalogo</a>
                            <a href="riepilogo.php" class="bottone">Vai al Pagamento</a>
                        </div>
                        <?php 
                    }
                ?>
            </div>
        </div>
    </body>
</html>
