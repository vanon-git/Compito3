# Studente: Mattia Vanon

# Indirizzo repository: https://github.com/vanon-git/Compito3

# 

# Descrizione del lavoro

# Per questo terzo homework ho esteso il sito di e-commerce per NFT ("Sappy Seals") realizzato nel secondo homework, introducendo la gestione del catalogo prodotti e degli ordini tramite file XML invece del database.

# 

# In particolare:

# 

# Il catalogo degli NFT è memorizzato in data.xml, con identificativo, prezzo, descrizione e disponibilità di ciascun prodotto.

# 

# Gli ordini effettuati dagli utenti vengono salvati in acquisti.xml, con username, data, totale e dettaglio degli articoli acquistati.

# 

# MySQL viene mantenuto solo per la gestione degli utenti e il tracciamento della spesa totale.

# 

# Per la manipolazione dei file XML ho utilizzato il DOM, seguendo gli esempi visti a lezione nella directory XML2. Ho inoltre implementato la validazione DTD tramite il metodo validate() di DOMDocument.

# 

# Come farlo funzionare

# Basta copiare la cartella del progetto dentro htdocs di XAMPP e lanciare la pagina install.php la prima volta, per creare il database e le tabelle necessarie.

# 

# Nota sulla configurazione

# Tutta la configurazione è centralizzata nel file DatiGenerali.php, che contiene host, username, password, nome del database (Compito3) e nomi delle tabelle.

# 

# Questo file viene incluso in connessione.php, che si occupa di collegarsi al server e, se il database non esiste ancora, di crearlo automaticamente.

