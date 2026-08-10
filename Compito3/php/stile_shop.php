<?php
$stile_shop = "
<style type=\"text/css\">
    /* importo i font da google */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Cinzel:wght@600;800&display=swap');

    :root{
        /* qui definisco i colori e le variabili da usare in tutto il sito */
        --bg: #fbf7ef;
        --bg2: #eef2ff;
        --surface: rgba(255,255,255,0.78);
        --surface-solid: #ffffff;

        --ink: #0f172a;
        --muted: #64748b;

        --primary: #4f46e5;     /* viola scuro */
        --primary-2: #06b6d4;   /* azzurro */
        --success: #16a34a;     /* verde */
        --danger: #ef4444;      /* rosso */
        --warning: #f59e0b;     /* arancione */

        --border: rgba(15, 23, 42, 0.12);
        --shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
        --shadow-2: 0 10px 22px rgba(15, 23, 42, 0.10);

        --radius: 18px;
        --radius-sm: 12px;
    }

    /* impostazioni di base per tutta la pagina */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Inter, Arial, sans-serif;
        color: var(--ink);
        /* sfondo complesso con vari gradienti sovrapposti */
        background:
            radial-gradient(1200px 650px at 20% 0%, var(--bg2), transparent 60%),
            radial-gradient(900px 500px at 90% 10%, #fff7ed, transparent 55%),
            linear-gradient(180deg, var(--bg), #ffffff 55%, #f8fafc);
        min-height: 100vh;
    }

    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }


    /* --- STILE DEL MENU IN ALTO (NAVBAR) --- */
    
    .navbar {
        position: sticky; /* rimane fissa in alto quando scorro */
        top: 0;
        z-index: 1000;
        padding: 16px 20px;
        background: rgba(251, 247, 239, 0.65);
        backdrop-filter: blur(10px); /* effetto vetro sfocato */
        border-bottom: 1px solid var(--border);
    }

    .contenuto-navbar {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 12px 14px;
    }

    .contenuto-navbar a { text-decoration: none; }

    /* titolo dello shop al centro con font particolare */
    .navbar h1 {
        width: 100%;
        text-align: center;
        font-family: Cinzel, serif;
        font-weight: 800;
        letter-spacing: 1px;
        font-size: 28px;
        color: var(--ink);
        line-height: 1.1;
        margin: 4px 0 2px 0;
    }

    /* bottoni del menu */
    .link-navbar {
        color: var(--ink);
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.7);
        border: 1px solid var(--border);
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.06);
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }

    .link-navbar:hover {
        transform: translateY(-2px);
        background: rgba(255,255,255,0.95);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.10);
    }

    /* stile per il menu orizzontale se serve in altre pagine */
    .menu-orizzontale {
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 12px 16px;
        background: rgba(255,255,255,0.7);
        border-bottom: 1px solid var(--border);
        backdrop-filter: blur(10px);
    }
    .menu-orizzontale li a{
        display: block;
        padding: 10px 14px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.7);
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .6px;
    }
    .menu-orizzontale li a:hover{ transform: translateY(-2px); }


    /* --- LAYOUT GENERALE --- */

    .container-principale {
        display: flex;
        flex-direction: row-reverse; /* inverto l'ordine col flex-reverse così la sidebar va a destra */
        gap: 26px;
        max-width: 1200px;
        margin: 26px auto;
        padding: 0 20px 40px 20px;
        align-items: flex-start;
    }

    /* adattamento per schermi piccoli (tablet/telefoni) */
    @media (max-width: 920px) {
        .container-principale { flex-direction: column; }
    }

    /* colonna laterale */
    .sidebar {
        min-width: 260px;
        max-width: 260px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        height: fit-content;
        box-shadow: var(--shadow-2);
        position: sticky;
        top: 90px;
    }

    @media (max-width: 920px) {
        .sidebar { min-width: auto; max-width: none; width: 100%; position: static; }
    }

    .sidebar h2 {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--muted);
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px dashed var(--border);
    }

    .etichetta-sidebar {
        color: var(--muted);
        font-size: 11px;
        margin-bottom: 4px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .valore-sidebar {
        margin-bottom: 14px;
        font-size: 15px;
        font-weight: 700;
        background: rgba(255,255,255,0.8);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px 12px;
    }


    /* --- GRIGLIA PRODOTTI (SHOP) --- */

    .container-meraviglie {
        flex: 1;
        display: grid; /* uso la griglia invece del flex perchè è più comoda per le schede */
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 18px;
        align-items: stretch;
    }

    .scheda-meraviglia {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        overflow: hidden;
        box-shadow: var(--shadow);
        display: flex;
        flex-direction: column;
        transform: translateZ(0);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .scheda-meraviglia:hover {
        transform: translateY(-6px);
        box-shadow: 0 26px 70px rgba(15, 23, 42, 0.18);
    }

    .scheda-meraviglia img {
        width: 100%;
        height: 190px;
        display: block;
        object-fit: cover;
        background: #e5e7eb; /* sfondo grigio di sicurezza se l'immagine manca */
        filter: saturate(1.05) contrast(1.03);
    }

    .contenuto-scheda {
        padding: 16px 16px 14px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 10px;
    }

    .titolo-scheda {
        font-size: 16px;
        font-weight: 800;
        line-height: 1.25;
    }

    .righa-carrello {
        margin-top: auto;
        display: flex;
        flex-direction: column;   /* metto gli elementi uno sotto l'altro */
        gap: 10px;
        align-items: stretch;
        padding-top: 12px;
        border-top: 1px dashed var(--border);
    }

    .prezzo-scheda {
        width: fit-content;
        background: rgba(79, 70, 229, 0.10);
        color: var(--primary);
        border: 1px solid rgba(79, 70, 229, 0.18);
        font-size: 15px;
        font-weight: 800;
        padding: 8px 12px;
        border-radius: 999px;
    }

    .bottone-aggiungi {
        width: 100%;
        background: linear-gradient(135deg, var(--primary), var(--primary-2));
        color: #ffffff;
        border: none;
        border-radius: 14px;
        font-weight: 800;
        font-size: 13px;
        padding: 11px 14px;
        cursor: pointer;
        box-shadow: 0 14px 24px rgba(79, 70, 229, 0.22);
        transition: transform .12s ease, filter .12s ease;
    }

    .bottone-aggiungi:hover {
        transform: translateY(-2px);
        filter: brightness(1.02);
    }

    /* stile semplice per il bottone compra */
    .bottone-compra {
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 11px 14px;
        font-weight: 800;
        cursor: pointer;
    }
    .bottone-compra:hover { filter: brightness(1.06); }


    /* --- MESSAGGI DI CONFERMA --- */

    .messaggio-aggiunto {
        background: rgba(22, 163, 74, 0.10);
        border: 1px solid rgba(22, 163, 74, 0.22);
        color: #14532d;
        padding: 16px 18px;
        margin: 18px auto;
        max-width: 1200px;
        border-radius: 16px;
        text-align: left;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.10);
    }

    .messaggio-aggiunto p {
        margin: 0 0 8px 0;
        font-size: 15px;
    }

    .messaggio-aggiunto strong { font-size: 16px; }
    .messaggio-aggiunto a { color: var(--primary); font-weight: 800; }
    .messaggio-aggiunto a:hover { text-decoration: underline; }


    /* --- PAGINA CARRELLO --- */
    
    .container-carrello { flex: 1; }

    .titolo-carrello {
        font-size: 30px;
        font-weight: 900;
        letter-spacing: -0.5px;
        margin-bottom: 18px;
    }

    .carrello-vuoto {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        padding: 54px 28px;
        text-align: center;
        box-shadow: var(--shadow);
    }

    .carrello-vuoto p {
        font-size: 18px;
        color: var(--muted);
        margin-bottom: 18px;
        font-weight: 600;
    }

    .carrello-vuoto a {
        background: var(--primary);
        color: white;
        padding: 12px 18px;
        border-radius: 999px;
        border: none;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 12px 22px rgba(79, 70, 229, 0.22);
    }

    .carrello-vuoto a:hover { filter: brightness(1.03); }

    .carrello-pieno {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        padding: 14px;
        margin-bottom: 18px;
        box-shadow: var(--shadow);
    }

    .articolo-carrello {
        padding: 14px 10px;
        border-bottom: 1px dashed var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap; /* vado a capo se non c'è spazio */
    }
    .articolo-carrello:last-child { border-bottom: none; }

    input[type='checkbox'] {
        width: 18px;
        height: 18px;
        margin-right: 6px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    .container-pulsanti {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }


    /* --- STILI VARI DEI BOTTONI --- */
    
    .bottone-rosso1 {
        background: var(--danger);
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 900;
        cursor: pointer;
        box-shadow: 0 12px 20px rgba(239, 68, 68, 0.18);
    }
    .bottone-rosso1:hover { filter: brightness(0.98); transform: translateY(-1px); }

    .bottone-rosso2 {
        background: rgba(255,255,255,0.9);
        color: var(--danger);
        border: 2px solid rgba(239, 68, 68, 0.55);
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 900;
        cursor: pointer;
    }
    .bottone-rosso2:hover { background: rgba(239, 68, 68, 0.10); }

    .bottone-grigio {
        background: #111827;
        color: #ffffff;
        border: none;
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
        margin-left: auto;
        box-shadow: 0 14px 26px rgba(17, 24, 39, 0.18);
    }
    .bottone-grigio:hover { filter: brightness(1.05); }

    .bottone-bianco {
        background: rgba(255,255,255,0.9);
        color: var(--ink);
        border: 1px solid var(--border);
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
    }
    .bottone-bianco:hover { background: rgba(255,255,255,1); }

    .bottone-acqua {
        background: linear-gradient(135deg, var(--primary-2), #22c55e);
        color: #052e2b;
        border: none;
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
        margin-left: auto;
        box-shadow: 0 14px 26px rgba(6, 182, 212, 0.22);
    }
    .bottone-acqua:hover { filter: brightness(1.03); }


    /* --- PAGINA RIEPILOGO --- */

    .container-riepilogo { flex: 1; }

    .titolo-riepilogo {
        font-size: 30px;
        font-weight: 900;
        letter-spacing: -0.5px;
        margin-bottom: 18px;
    }

    .riepilogo-vuoto {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        padding: 54px 28px;
        text-align: center;
        box-shadow: var(--shadow);
    }

    .riepilogo-vuoto p {
        font-size: 18px;
        color: var(--muted);
        margin-bottom: 18px;
        font-weight: 600;
    }

    .riepilogo-vuoto a {
        background: var(--primary);
        color: white;
        padding: 12px 18px;
        border-radius: 999px;
        border: none;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 12px 22px rgba(79, 70, 229, 0.22);
    }
    .riepilogo-vuoto a:hover { filter: brightness(1.03); }

    .riepilogo-pieno {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        padding: 14px;
        margin-bottom: 18px;
        box-shadow: var(--shadow);
    }

    .articolo-riepilogo {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap; 
        padding: 14px 10px;
        border-bottom: 1px dashed var(--border);
    }
    .articolo-riepilogo:last-child { border-bottom: none; }

    .immagine-articolo {
        width: 72px;
        height: 72px;
        flex: 0 0 72px;   /* dimensione fissa per l'immagine */
        object-fit: cover;
        border-radius: 16px;
        background: #e5e7eb;
        border: 1px solid var(--border);
    }

    .info-articolo {
        flex: 1;
        min-width: 180px; 
    }

    .nome-articolo {
        font-size: 15px;
        font-weight: 900;
        color: var(--ink);
    }

    .prezzo-articolo {
        margin-left: auto;
        white-space: nowrap;
        font-size: 16px;
        font-weight: 900;
        color: var(--primary);
        background: rgba(79, 70, 229, 0.10);
        border: 1px solid rgba(79, 70, 229, 0.18);
        padding: 8px 12px;
        border-radius: 999px;
    }

    .container-prezzoTot {
        background: #0f172a;
        color: #ffffff;
        border-radius: 22px;
        padding: 18px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 22px 55px rgba(15, 23, 42, 0.22);
    }

    .titolo-totale {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.75);
        font-weight: 800;
    }

    .valore-totale {
        font-size: 28px;
        font-weight: 900;
        color: #ffffff;
    }

    .container-bottoni {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
        align-items: center;
    }

    .bottone-grey {
        background: rgba(255,255,255,0.9);
        color: var(--ink);
        border: 1px solid var(--border);
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
    }
    .bottone-grey:hover { background: rgba(255,255,255,1); }

    .bottone-verde {
        background: var(--success);
        color: white;
        border: none;
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        cursor: pointer;
        box-shadow: 0 14px 26px rgba(22, 163, 74, 0.18);
    }
    .bottone-verde:hover { filter: brightness(1.02); }


    /* --- PAGINA PAGAMENTO --- */
    
    .container-pagamento { flex: 1; }

    .container-successo {
        background: rgba(255,255,255,0.85);
        border: 1px solid rgba(22, 163, 74, 0.25);
        border-radius: 22px;
        padding: 34px 24px;
        text-align: center;
        margin-bottom: 18px;
        box-shadow: var(--shadow);
    }

    .icona-successo {
        background: rgba(22, 163, 74, 0.12);
        color: var(--success);
        width: 84px;
        height: 84px;
        border-radius: 24px; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 44px;
        font-weight: 900;
        margin: 0 auto 16px auto;
        border: 1px solid rgba(22, 163, 74, 0.20);
    }

    .titolo-successo {
        font-size: 26px;
        color: #14532d;
        margin-bottom: 10px;
        font-weight: 900;
    }

    .testo-successo {
        font-size: 16px;
        color: var(--muted);
        margin-bottom: 18px;
        font-weight: 600;
    }

    .dettaglio-spesa {
        background: rgba(15, 23, 42, 0.04);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 16px;
        text-align: left;
    }

    .importo-ora {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 14px;
        color: var(--ink);
        border-bottom: 1px dashed var(--border);
    }
    .importo-ora:last-child { border-bottom: none; }
    .importo-ora strong { color: var(--primary); }

    .spesa-totale {
        display: flex;
        justify-content: space-between;
        border-top: 1px solid var(--border);
        margin-top: 12px;
        padding-top: 12px;
        font-size: 16px;
        font-weight: 900;
        color: var(--ink);
    }
    .spesa-totale strong { color: var(--ink); }

    .container-errore {
        background: rgba(255,255,255,0.85);
        border: 1px solid rgba(239, 68, 68, 0.25);
        border-radius: 22px;
        padding: 34px 24px;
        text-align: center;
        margin-bottom: 18px;
        box-shadow: var(--shadow);
    }

    .icona-errore {
        background: rgba(239, 68, 68, 0.10);
        color: var(--danger);
        width: 84px;
        height: 84px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 44px;
        font-weight: 900;
        margin: 0 auto 16px auto;
        border: 1px solid rgba(239, 68, 68, 0.18);
    }

    .titolo-errore {
        font-size: 26px;
        color: #7f1d1d;
        margin-bottom: 10px;
        font-weight: 900;
    }

    .testo-errore {
        font-size: 16px;
        color: var(--muted);
        font-weight: 600;
    }

    .container-azioni {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 14px;
    }

    .bottone-back {
        background: rgba(255,255,255,0.95);
        color: var(--ink);
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
        border: 1px solid var(--border);
    }
    .bottone-back:hover { background: rgba(255,255,255,1); }

    .bottone {
        background: linear-gradient(135deg, var(--primary), var(--primary-2));
        color: #ffffff;
        border: none;
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 14px 26px rgba(79, 70, 229, 0.22);
    }
    .bottone:hover { filter: brightness(1.02); }

</style>
";
?>
