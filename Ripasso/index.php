<?php
/**
 * index.php — Home Page
 * ──────────────────────────────────────────────────────────────────────────
 * Prima pagina dell'applicativo: mostra un messaggio di benvenuto,
 * la data e l'ora corrente, e un link alla pagina di registrazione.
 *
 * Non legge né scrive dati: è una pagina puramente informativa.
 * ──────────────────────────────────────────────────────────────────────────
 */

/*
 * date_default_timezone_set()
 * ─────────────────────────────────────────────────────────────────────────
 * Imposta il fuso orario usato da tutte le funzioni di data/ora di PHP
 * (date(), time(), strtotime(), ecc.) per questa esecuzione dello script.
 *
 * Senza questa riga PHP userebbe il fuso orario configurato nel file
 * php.ini del server, che potrebbe essere UTC o qualsiasi altro valore.
 * Impostandolo esplicitamente ci assicuriamo che l'ora mostrata sia
 * sempre quella italiana, indipendentemente da dove gira il server.
 *
 * 'Europe/Rome' gestisce automaticamente anche il cambio ora legale/solare.
 */
date_default_timezone_set('Europe/Rome');

/*
 * date('d/m/Y')
 * ─────────────────────────────────────────────────────────────────────────
 * Restituisce la data odierna formattata come stringa.
 * I caratteri del formato significano:
 *   d → giorno con zero iniziale (01–31)
 *   m → mese con zero iniziale  (01–12)
 *   Y → anno con 4 cifre        (es. 2026)
 *
 * Risultato esempio: "26/03/2026"
 */
$dataOggi = date('d/m/Y');

/*
 * date('H:i:s')
 * ─────────────────────────────────────────────────────────────────────────
 * Restituisce l'ora corrente nel momento in cui PHP elabora la pagina.
 *   H → ore in formato 24h con zero iniziale (00–23)
 *   i → minuti con zero iniziale             (00–59)
 *   s → secondi con zero iniziale            (00–59)
 *
 * Risultato esempio: "14:35:07"
 *
 * Questo valore viene iniettato nell'HTML come valore iniziale dell'orologio.
 * Poi JavaScript lo aggiorna ogni secondo lato client per mantenerlo vivo.
 */
$oraOra = date('H:i:s');

/*
 * Array $giorniIta
 * ─────────────────────────────────────────────────────────────────────────
 * Mappa il numero del giorno della settimana (restituito da date('N'))
 * al nome italiano corrispondente.
 *
 * date('N') segue lo standard ISO 8601:
 *   1 = Lunedì … 7 = Domenica
 *
 * Usiamo un array associativo con indice esplicito (1–7) invece di un
 * array normale (0–6) per evitare di dover fare calcoli di offset.
 */
$giorniIta = [
        1 => 'Lunedì',
        2 => 'Martedì',
        3 => 'Mercoledì',
        4 => 'Giovedì',
        5 => 'Venerdì',
        6 => 'Sabato',
        7 => 'Domenica',
];

/*
 * Array $mesiIta
 * ─────────────────────────────────────────────────────────────────────────
 * Mappa il numero del mese (1–12, da date('n')) al nome italiano.
 *
 * date('n') restituisce il mese senza zero iniziale (1 invece di 01),
 * che corrisponde esattamente agli indici di questo array.
 *
 * I mesi sono in minuscolo perché verranno usati nel mezzo di una frase
 * ("26 marzo 2026"), non come titolo.
 */
$mesiIta = [
        1  => 'gennaio',   2  => 'febbraio', 3  => 'marzo',
        4  => 'aprile',    5  => 'maggio',   6  => 'giugno',
        7  => 'luglio',    8  => 'agosto',   9  => 'settembre',
        10 => 'ottobre',   11 => 'novembre', 12 => 'dicembre',
];

/*
 * $dataEstesa
 * ─────────────────────────────────────────────────────────────────────────
 * Costruisce la data in formato leggibile in italiano.
 * Esempio: "Giovedì, 26 marzo 2026"
 *
 * Pezzi della concatenazione (operatore '.'):
 *   $giorniIta[(int)date('N')]  → nome del giorno. (int) converte il valore
 *                                 restituito da date() da stringa a intero,
 *                                 necessario per usarlo come chiave dell'array.
 *   ', '                        → separatore virgola-spazio
 *   date('j')                   → giorno del mese SENZA zero iniziale (es. 26)
 *   ' '                         → spazio
 *   $mesiIta[(int)date('n')]    → nome del mese in italiano
 *   ' '                         → spazio
 *   date('Y')                   → anno a 4 cifre
 */
$dataEstesa = $giorniIta[(int)date('N')] . ', ' . date('j') . ' ' . $mesiIta[(int)date('n')] . ' ' . date('Y');

/*
 * $paginaCorrente
 * ─────────────────────────────────────────────────────────────────────────
 * basename() estrae solo il nome del file dal percorso completo contenuto
 * in $_SERVER['PHP_SELF'].
 *
 * $_SERVER è un array superglobale (accessibile ovunque senza dichiararlo)
 * che PHP popola con informazioni sulla richiesta HTTP corrente.
 * $_SERVER['PHP_SELF'] contiene il percorso del file PHP in esecuzione,
 * ad esempio "/biblioteca/index.php".
 *
 * basename("/biblioteca/index.php") → "index.php"
 *
 * Questa variabile viene usata nel blocco della navigazione per aggiungere
 * la classe CSS "active" al link corrispondente alla pagina corrente,
 * evidenziando visivamente dove si trova l'utente nel sito.
 */
$paginaCorrente = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto — Biblioteca Comunale</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>

<!-- ═══════════════════════════════════════════════════════════════════════
     HEADER / NAVIGAZIONE
     ───────────────────────────────────────────────────────────────────────
     Il blocco nav è scritto direttamente in ogni pagina (inline) invece
     di usare un file separato incluso con include/require.

     Perché? L'include di nav.php causava un loop infinito in alcune
     configurazioni di server, perché il server serviva nav.php come
     pagina principale ripetendosi all'infinito.

     La classe "active" viene aggiunta con un operatore ternario PHP:
       condizione ? Valore_se_vera : valore_se_falsa
     Esempio:
       $paginaCorrente === 'index.php' ? 'active' : ''
     Se la pagina corrente è index.php stampa "active", altrimenti "".
     ═══════════════════════════════════════════════════════════════════════ -->
<header class="site-header">
    <div class="header-inner">

        <!-- Logo cliccabile che riporta sempre alla home -->
        <a href="index.php" class="logo">
            <span class="logo-icon">📚</span>
            <span class="logo-text">BIBLIO<em>TECA</em></span>
            <span class="logo-sub">Comunale</span>
        </a>

        <!-- Barra di navigazione principale -->
        <nav class="main-nav">

            <!-- Link Home: riceve la classe "active" se siamo su index.php -->
            <a class="nav-link <?= $paginaCorrente === 'index.php' ? 'active' : '' ?>"
               href="index.php">
                <span class="nav-icon">🏠</span> Home
            </a>

            <!-- Link Registrazione: attivo su registrazione.php -->
            <a class="nav-link <?= $paginaCorrente === 'registrazione.php' ? 'active' : '' ?>"
               href="registrazione.php">
                <span class="nav-icon">✍️</span> Registrazione
            </a>

            <!-- Link Debug: attivo su debug.php.
                 Ha anche la classe "debug-link" che il CSS può usare
                 per dargli uno stile diverso dagli altri link (es. Colore). -->
            <a class="nav-link debug-link <?= $paginaCorrente === 'debug.php' ? 'active' : '' ?>"
               href="debug.php">
                <span class="nav-icon">🛠️</span> Debug
            </a>

        </nav>
    </div>
</header>

<main>

    <!-- ── HERO ─────────────────────────────────────────────────────────
         Sezione di benvenuto in cima alla pagina.
         Contiene un badge decorativo, il titolo principale e un sottotitolo.
         ────────────────────────────────────────────────────────────────── -->
    <section class="page-hero">
        <div class="hero-badge">🏛️ Biblioteca Comunale</div>
        <h1>Benvenuto nella<br><span>Biblioteca</span></h1>
        <p class="hero-sub">Il luogo dove ogni storia trova casa e ogni lettore trova la sua storia.</p>
    </section>

    <!-- ── OROLOGIO / DATA ───────────────────────────────────────────────
         Mostra la data per esteso e l'ora corrente.

         La data ($dataEstesa) è generata da PHP lato server: viene calcolata
         una sola volta al caricamento della pagina e non cambia finché
         la pagina non viene ricaricata.

         L'ora invece ha id="clock": JavaScript la aggiorna ogni secondo
         (vedi il blocco <script> in fondo alla pagina) così l'orologio
         rimane sempre preciso senza ricaricare la pagina.

         htmlspecialchars() converte caratteri come < > & in entità HTML
         sicure (&lt; &gt; &amp;) per prevenire attacchi XSS.
         ────────────────────────────────────────────────────────────────── -->
    <section class="clock-section">
        <div class="clock-card">

            <!-- Data per esteso: statica, aggiornata solo al ricaricamento pagina -->
            <div class="clock-date"><?= htmlspecialchars($dataEstesa) ?></div>

            <!-- Orologio: valore iniziale da PHP, poi aggiornato da JavaScript -->
            <div class="clock-time" id="clock"><?= htmlspecialchars($oraOra) ?></div>

            <div class="clock-label">Ora locale — Europa/Roma</div>
        </div>
    </section>

    <!-- ── CARDS INFORMATIVE ─────────────────────────────────────────────
         Tre card disposte in griglia CSS:
           1. Catalogo  → info generali sulla biblioteca
           2. Orari     → orari di apertura
           3. CTA card  → invito all'iscrizione con link a registrazione.php
         ────────────────────────────────────────────────────────────────── -->
    <section class="info-grid">

        <div class="info-card">
            <div class="info-icon">📖</div>
            <h3>Catalogo</h3>
            <p>Oltre 12.000 volumi disponibili tra narrativa, saggistica, fumetti e riviste specializzate.</p>
        </div>

        <div class="info-card">
            <div class="info-icon">🕐</div>
            <h3>Orari</h3>
            <p>Lunedì – Venerdì: 9:00 – 20:00<br>Sabato: 9:00 – 13:00<br>Domenica: chiuso</p>
        </div>

        <!-- Card con classe aggiuntiva "cta-card" per stile evidenziato nel CSS -->
        <div class="info-card cta-card">
            <div class="info-icon">🪪</div>
            <h3>Vuoi iscriverti?</h3>
            <p>Registrati gratuitamente e ottieni la tua tessera per prendere libri in prestito.</p>
            <a href="registrazione.php" class="btn btn-primary">ISCRIVITI ORA →</a>
        </div>

    </section>

</main>

<script>
    /**
     * Orologio in tempo reale — lato client (JavaScript)
     * ──────────────────────────────────────────────────────────────────────────
     * PHP stampa l'ora corrente nel momento in cui genera la pagina HTML.
     * Tuttavia, una volta che la pagina è nel browser, quella stringa è
     * "congelata": non si aggiorna da sola.
     *
     * setInterval(funzione, millisecondi) esegue la funzione passata come
     * primo argomento ogni N millisecondi in modo ripetuto e indefinito.
     * Con 1000 ms = 1 secondo l'orologio avanza in tempo reale.
     */
    setInterval(function () {

        /*
         * new Date()
         * ─────────────────────────────────────────────────────────────────
         * Crea un oggetto Date con la data e l'ora CORRENTE del browser.
         * È diverso dall'ora stampata da PHP: PHP usa l'ora del server,
         * JavaScript usa l'ora del dispositivo dell'utente.
         * Nella pratica coincidono se il fuso orario è configurato correttamente.
         */
        const now = new Date();

        /*
         * pad(n) — Arrow function
         * ─────────────────────────────────────────────────────────────────
         * Converte un numero in stringa e aggiunge uno zero iniziale
         * se il numero è minore di 10, per mantenere sempre 2 cifre.
         *
         * String(n)         → converte il numero in stringa
         * .padStart(2, '0') → riempi a sinistra con '0' fino a 2 caratteri
         *
         * Esempi:
         *   pad(9)  → "09"
         *   pad(23) → "23"  (già 2 cifre, nessuna modifica)
         */
        const pad = n => String(n).padStart(2, '0');

        /*
         * Costruisce la stringa "HH:MM:SS" concatenando ore, minuti e secondi.
         * I metodi dell'oggetto Date restituiscono numeri interi:
         *   getHours()   → ore    (0–23)
         *   getMinutes() → minuti (0–59)
         *   getSeconds() → secondi (0–59)
         *
         * Ogni valore viene passato a pad() per garantire sempre 2 cifre.
         */
        const oraStr = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());

        /*
         * document.getElementById('clock')
         * ─────────────────────────────────────────────────────────────────
         * Cerca nel DOM (la struttura HTML della pagina) l'elemento con
         * id="clock" — che è il <div class="clock-time"> dell'orologio.
         *
         * .textContent = oraStr
         * Sostituisce il testo visibile all'interno di quell'elemento.
         * Usiamo .textContent (non .innerHTML) perché tratta il valore
         * come testo puro, senza interpretare eventuali tag HTML,
         * rendendo l'operazione più sicura.
         */
        document.getElementById('clock').textContent = oraStr;

    }, 1000); /* ripete ogni 1000 millisecondi = 1 secondo */
</script>

</body>
</html>