<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();

    /* Se c'è già una sessione attiva la chiudo per evitare problemi con il nuovo login */
    if (isset($_SESSION['accessoPermesso'])){
        unset($_SESSION);
        session_destroy();
    }

    /* Includo il file per connettermi al database */
    require_once("./connessione.php");

    // Controllo se il form è stato inviato
    if (isset($_POST['invio'])){          
        if (empty($_POST['username']) || empty($_POST['password'])){
            echo "<p>Non hai inserito i dati,Riprova</p>";
        }else {
            // Cerco nel database se esiste un utente con questo username e password
            $sql = "SELECT * FROM " . TBL_UTENTI . " WHERE username = '$_POST[username]' AND password = '$_POST[password]'";    

            // Eseguo la query
            if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
                printf("Errore, la query non ha risultato\n");
                exit();
            }
            
            // Prendo i risultati della query
            $row = mysqli_fetch_array($resultQ);

            if($row) {  
                session_start();
                $_SESSION['nome']=$row['nome'];
                $_SESSION['cognome']=$row['cognome'];
                $_SESSION['username']=$_POST['username'];
                $_SESSION['totaleAcquisti']=$row['totaleAcquisti'];
                $_SESSION['dataLogin']=time();
                $_SESSION['accessoPermesso']=1000;
                header('Location: shop.php');    // Rimando l'utente alla pagina dello shop
                exit();
            }else 
                echo "<p>Utente non trovato, riprova oppure se non sei registrato torna alla home e clicca su registrati</p>";
        }
    }

    // Chiudo la connessione
    $mysqliConnection->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <!-- Collego il foglio di stile -->
  <link rel="stylesheet" href="../css/style3.css" type="text/css">
  <title>Pagina Login</title>
</head>

<body>

  <!-- Uso il div con la classe login-card per lo stile -->
  <div class="login-card"> 
      <h3>Login Form</h3>
      <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
          <p>username:</p>
          <input type="text" name="username" size="30" />
          
          <p>password:</p>
          <input type="password" name="password" size="30" />

          <br><br>

          <input type="submit" name="invio" value="accedi">
          <input type="reset" name="reset" value="reset">

          <input type="button" name="registrati" value="registrati" onclick="window.location.href='register.php'">

      </form>
  </div>

  <hr />
</body>
</html>
