<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();

    /* Pulisco l'array della sessione e la distruggo per fare il logout correttamente */
    unset($_SESSION);
    session_destroy();
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
       <head>
            <title>Homework1-1/3</title>
            <!-- collego il css usando time() per evitare che il browser salvi la vecchia versione in cache -->
            <link rel="stylesheet" href="../css/Style.css?ts=<?=time()?>&quot"" type="text/css" />
       </head>

       <body>
             
             <!-- Colonna di destra con le immagini rare -->
             <div class="header1">
                     <h2><Strong>Queste sono le Seal pi&ugrave rare</Strong></h2>
                     <div>
                            <img class="img" src="../img./imgrara1.jpg" />
                            <img class="img" src="../img./imgrara2.jpg" />
                            <img class="img" src="../img./imgrara3.jpg" />
                            <img class="img" src="../img./imgrara4.jpg" />
                            <img class="img" src="../img./imgrara5.jpg" />
                            <img class="img" src="../img./imgrara6.jpg" />
                            <img class="img" src="../img./imgrara7.jpg" />
                            <img class="img" src="../img./imgrara8.jpg" />
                            <img class="img" src="../img./imgrara9.jpg" />                     
                     </div> 
              </div>
               
              <!-- Colonna di sinistra con le immagini costose -->
              <div class="header2">
                     <h2><Strong>Queste sono le Seal pi&ugrave costose</Strong></h2>
                     <div >
                           <img class="img" src="../img/imgcostosa1.png" />
                           <img class="img" src="../img/imgcostosa2.png" />
                           <img class="img" src="../img/imgcostosa3.png" />
                           <img class="img" src="../img/imgcostosa4.png" />
                           <img class="img" src="../img/imgcostosa5.png" />
                           <img class="img" src="../img/imgcostosa6.jpg" />
                           <img class="img" src="../img/imgcostosa7.png" />
                           <img class="img" src="../img/imgcostosa8.png" />
                           <img class="img" src="../img/imgcostosa9.png" />   
                     </div>        
              </div>
              
              <!-- Intestazione in alto con i bottoni login e logout -->
              <div class="header">
                     <ul>
                     <h1>Sappy Seals NFT</h1>
                     <li class="menu-link-a"><a href="../php/login.php"><Strong>Login</Strong></a></li>
                     <li class="menu-link-b"><a href="../php/logout.php"><Strong>Logout</Strong></a></li>
                     </ul>
              </div>

              <!-- Parte centrale con il menu e il messaggio di saluto -->
              <div  class="center">
                     <ul>
                     <li class="menu-link"><a href='../index.html'><strong>HOME</strong></a></li>
                     <li class="menu-link"><a href='../file/NFT.html'><strong>Cos'&egrave un NFT?</strong></a></li>
                     <li class="menu-link"><a href='../file/Blockchain.html'><strong>Cos'&egrave la blockchain?</strong></a></li>
                     <li class="menu-link"><a href='../php/shop.php'><strong>Carrello</strong></a></li>
                     </ul>
              <div class="stile-logout">
                     <h1><em><Strong>Logout effettuato con successo </Strong></em></h1>
                     <h1><em><Strong>Grazie per aver visitato il sito , a presto </Strong></em></h1>
              <div class="menu-link">
                <a href="login.php" >Torna al Login</a>
            </div>
              <div class="menu-link">
                <a href="../index.html" >Vai alla Home</a>
            </div>
        </div>

        </div>
    </body>
</html>

