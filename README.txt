Studente: Mattia Vanon
Link GitHub: https://github.com/vanon-git/Compito2

Descrizione del lavoro
Per questo secondo homework ho realizzato un piccolo sito di e-commerce per la vendita di NFT (i "Sappy Seals"). Il sito permette di registrarsi, fare il login, aggiungere prodotti al carrello e completare l'acquisto, gestendo tutto tramite sessioni PHP e database MySQL.

Per svolgerlo ho preso spunto dagli esercizi visti a lezione, in particolare:

Gli esercizi PHP-13 e PHP-14 per capire come gestire il carrello e come popolare il database tramite script.

L'esercizio PHP-15 per imparare a separare i file di connessione e includerli nelle varie pagine (come connessione.php, datiGenerali.php e il menu).

L'esempio del carrello della spesa (Lezione 6) per la gestione della sessione utente.

Come farlo funzionare
Basta copiare la cartella del progetto dentro la cartella htdocs di XAMPP e lanciare la pagina install.php la prima volta per creare il database e le tabelle.

Nota sulle password del Database:
Per seguire lo schema visto a lezione ed evitare di avere dati come localhost o i nomi delle tabelle "hard coded" nel codice, ho centralizzato tutta la configurazione.
Se usate una password diversa per MySQL (di solito su XAMPP è vuota, ma su MAMP è "root"), basterà cambiarla in un solo file:

datiGenerali.php: Contiene tutti i dati su user mysql, db name, password e i nomi delle tabelle.

Questo file viene poi incluso automaticamente nei due file principali:

connessione.php: in cui a regime il sito effettua la connessione al db per funzionare.

install.php: in cui si effettua la connessione iniziale al dbms per poi creare il database e le tabelle.