<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // se non ho il permesso di accesso, rimando al login
    if (!isset($_SESSION['accessoPermesso']))
        header('Location: login.php');
    
    require_once("./stile_shop.php");

    // variabile per stampare messaggi di conferma
    $messaggio = "";

    // se ho cliccato su elimina e ho selezionato qualcosa
    if (isset($_POST['eliminaSelezionati']) && isset($_POST['eliminandi'])) {
        foreach ($_POST['eliminandi'] as $k => $indiceDaEliminare) {
            unset($_SESSION['carrello'][$indiceDaEliminare]);
        }
        $messaggio = "NFT selezionati eliminati dal carrello!";
    } else {
        // se ho cliccato su svuota tutto
        if (isset($_POST['svuotaCarrello'])) {
            $_SESSION['carrello'] = array();
            $messaggio = "Carrello svuotato completamente!";
        }

        // se provo a eliminare senza selezionare nulla
        if (isset($_POST['eliminaSelezionati']))
            $messaggio = "Seleziona i NFT che vuoi eliminare!";
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Il Tuo Carrello - Sappy Seals Shop</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <?php echo $stile_shop; ?>
    </head>
    <body>
        <?php require("menu_shop.php"); ?>
        
        <?php if ($messaggio != "") { ?>      
            <div class="messaggio-aggiunto">
                <p><?php echo $messaggio; ?></p>
            </div>
        <?php } ?>

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

            <!-- parte centrale del carrello -->
            <div class="container-carrello">
                <h2 class="titolo-carrello">Il Tuo Carrello</h2>

                <?php 
                    // se il carrello è vuoto mostro l'avviso
                    if (empty($_SESSION['carrello'])){
                ?>
                        <div class="carrello-vuoto">
                            <p>Il tuo carrello è vuoto</p>
                            <a href="shop.php">Vai al Catalogo</a<<<<<<<<<<<<<<<<>
                        </div>
                <?php 
                    // altrimenti mostro la lista dei prodotti
                    } else { 
                ?>
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="carrello-pieno">
                                <?php 
                                    // scorro tutti gli articoli salvati nella sessione
                                    foreach ($_SESSION['carrello'] as $indice => $nomeNFT){
                                     ?>
                                        <div class="articolo-carrello">
                                            <input type="checkbox" name="eliminandi[]" value="<?php echo $indice; ?>" /> <?php echo $nomeNFT; ?>
                                        </div>
                                        <?php
                                    } 
                                ?>
                            </div>

                            <div class="container-pulsanti">
                                <input type="submit" name="eliminaSelezionati" class="bottone-rosso1" value="Elimina Selezionati" />
                                
                                <input type="submit" name="svuotaCarrello" class="bottone-rosso2" value="Svuota Carrello" />

                                <input type="reset" name="annullaSelezionati" class="bottone-bianco" value="Deseleziona Tutto" />

                                <a href="shop_xml.php" class="bottone-grigio"> Continua acquisti </a>
                                
                                <a href="riepilogo_xml.php" class="bottone-acqua"> Vai al pagamento </a>
                            </div>
                        </form>
                        <?php 
                    } 
                ?>
            </div>
        </div>
    </body>
</html>
