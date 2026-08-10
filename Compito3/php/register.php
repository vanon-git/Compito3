<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    // includo il file con i parametri di connessione
    require_once("./connessione.php");

    // se è stato premuto il tasto invio
    if(isset($_POST['invio'])) {
        
        // controllo che tutti i campi siano stati compilati
        if(($_POST['nome']) && ($_POST['cognome']) && ($_POST['username']) && ($_POST['password']) ) {
        
            // preparo la query per vedere se l'username è già presente nel db
            $sql = "SELECT * FROM " . TBL_UTENTI . " WHERE username = '$_POST[username]'";
                
            // eseguo la query e controllo se ci sono errori
            if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
                printf("Errore nel controllo dei dati.\n");
                exit();
            }

            $row = mysqli_fetch_array($resultQ);

            // se l'utente esiste già avviso, altrimenti procedo
            if($row){
                printf("<em> Se sei già registarto, <a href='login.php'>accedi qui</a>.\n");
            }else{
                
                // inserisco il nuovo utente nel database
                $sql = "INSERT INTO " . TBL_UTENTI . " (nome, cognome, username, password, totaleAcquisti) 
                VALUES ('$_POST[nome]', '$_POST[cognome]','$_POST[username]','$_POST[password]', 0)";

                // eseguo l'inserimento e verifico che sia andato a buon fine
                if(!($resultQ = mysqli_query($mysqliConnection, $sql))) {
                    printf("Si è verificato un errore. Impossibile registrarsi.\n");
                    exit();
                }else{
                    printf("<p>Registrazione completata con successo! 
                                <br /><a href='login.php'>Clicca qui per effettuare il login</a></p>");
                }
            }
        }else{
            printf("<em>Tutti i campi sono obbligatori.</em>");
        }
    }
    
    // chiudo la connessione al database
    $mysqliConnection->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <link rel="stylesheet" href="../css/style3.css" type="text/css">
  <title>Pagina Registrazione</title>
</head>

<body>
  
  <!-- uso la classe login-card per avere lo stesso stile del login -->
  <div class="login-card">
      <h3>Form per la Registrazione</h3>
      
      <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
          
          <p>nome:</p> 
          <input type="text" name="nome" size="30" />
          
          <p>cognome:</p> 
          <input type="text" name="cognome" size="30" />
          
          <p>username:</p> 
          <input type="text" name="username" size="30" />
          
          <p>password:</p> 
          <input type="password" name="password" size="30" />

          <br><br>

          <input type="submit" name="invio" value="Registrati" >
          <input type="reset" name="reset" value="reset" >
      
      </form>
  </div>

  <hr />
</body>
</html>
