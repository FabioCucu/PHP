<?php
/**
 * registrazione.php — Registrazione Utenti
 * ──────────────────────────────────────────────────────────────────────────
 * Seconda pagina dell'applicativo: permette di registrare un nuovo utente
 * nella biblioteca compilando un form con nome, cognome, data e password.
 *
 * È un "self-processing form": invia i dati a se stessa (action="registrazione.php").
 * In base al metodo HTTP distingue due situazioni:
 *   GET  → prima visita, mostra il form vuoto
 *   POST → form inviato, valida i dati e li salva su file JSON
 * ──────────────────────────────────────────────────────────────────────────
 */

/*
 * require_once 'storage.php'
 * ─────────────────────────────────────────────────────────────────────────
 * Include il file storage.php che contiene la classe Storage.
 * require_once (a differenza di include) genera un errore fatale se il file
 * non viene trovato, interrompendo l'esecuzione: preferibile a include
 * perché senza Storage il resto dello script non funzionerebbe comunque.
 * "_once" garantisce che il file venga caricato una sola volta anche se
 * require_once venisse chiamato più volte (protezione da doppia definizione
 * della classe, che in PHP causerebbe un errore fatale).
 */
require_once 'storage.php';

/*
 * date_default_timezone_set('Europe/Rome')
 * ─────────────────────────────────────────────────────────────────────────
 * Imposta il fuso orario per tutte le funzioni date/ora di questo script.
 * Necessario per calcolare correttamente la data odierna da usare come
 * valore di default nel campo "data_iscrizione".
 */
date_default_timezone_set('Europe/Rome');

/*
 * $defaultData
 * ─────────────────────────────────────────────────────────────────────────
 * Data odierna nel formato Y-m-d (es. "2026-03-26"), richiesto dall'input
 * HTML di tipo date (type="date"). Viene usata come valore pre-compilato
 * nel campo data del form, così l'operatore non deve digitarla ogni volta.
 */
$defaultData = date('Y-m-d');

/*
 * Variabili di feedback
 * ─────────────────────────────────────────────────────────────────────────
 * $msg     → testo del messaggio da mostrare all'utente (successo o errore)
 * $msgTipo → stringa "success" o "error", usata come classe CSS per colorare
 *             il box del messaggio in verde o rosso
 *
 * Inizializzate a stringa vuota: se non c'è stato nessun POST, non verrà
 * mostrato alcun messaggio (il blocco <?php if ($msg !== ''): ?> non stampa nulla).
 */
$msg     = '';
$msgTipo = '';

/*
 * Variabili del form
 * ─────────────────────────────────────────────────────────────────────────
 * Queste variabili vengono usate nei campi value="..." del form HTML per
 * RIPOPOLARE i campi con i valori già inseriti in caso di errore.
 * In questo modo l'utente non deve riscrivere tutto da capo se ad esempio
 * ha sbagliato la conferma password.
 *
 * Inizializzate a stringa vuota o alla data di default: sono i valori
 * mostrati al primo caricamento della pagina (GET).
 */
$nome           = '';
$cognome        = '';
$dataIscrizione = $defaultData;

/* ── Gestione POST ──────────────────────────────────────────────────────── */

/*
 * $_SERVER['REQUEST_METHOD']
 * ─────────────────────────────────────────────────────────────────────────
 * $_SERVER è un array superglobale popolato da PHP con le informazioni
 * sulla richiesta HTTP corrente.
 * 'REQUEST_METHOD' contiene il metodo usato: 'GET', 'POST', 'PUT', ecc.
 *
 * Confrontando con 'POST' distinguiamo se il form è stato inviato oppure
 * no. Solo in caso di POST processiamo i dati.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
     * Raccolta e sanificazione dati dal superglobale $_POST
     * ─────────────────────────────────────────────────────────────────────
     * $_POST è un array associativo che PHP popola automaticamente con i
     * campi del form quando il metodo è POST. Le chiavi corrispondono
     * all'attributo "name" di ogni <input>.
     *
     * ?? '' (null coalescing operator)
     *   → se la chiave non esiste in $_POST restituisce '' invece di null.
     *     Evita Notice "Undefined index".
     *
     * trim()
     *   → rimuove spazi, tab e ritorni a capo dall'inizio e dalla fine
     *     della stringa. Un utente che inserisce "  Mario  " ottiene "Mario".
     *     Importante per evitare che campi con soli spazi passino il
     *     controllo "campo non vuoto".
     *
     * NOTA: le variabili $password e $passwordConf NON vengono dichiarate
     * fuori dall'if, perché le password non devono mai essere mostrate
     * nel form (i campi password non vengono mai ripopolati per sicurezza).
     */
    $nome           = trim($_POST['nome']           ?? '');
    $cognome        = trim($_POST['cognome']         ?? '');
    $dataIscrizione = trim($_POST['data_iscrizione'] ?? $defaultData);
    $password       = trim($_POST['password']        ?? '');
    $passwordConf   = trim($_POST['password_conf']   ?? '');

    /* ── Validazione lato server ─────────────────────────────────────── */

    /*
     * Controllo 1: campi obbligatori
     * ─────────────────────────────────────────────────────────────────────
     * Verifichiamo che nessun campo sia rimasto vuoto.
     * La validazione HTML5 (attributo "required") avviene solo nel browser
     * e può essere facilmente aggirata (disabilitando JavaScript, usando
     * strumenti come curl o Postman). La validazione lato server è quindi
     * SEMPRE necessaria come linea di difesa principale.
     *
     * L'operatore || (OR logico) è true se almeno una condizione è vera:
     * se uno qualsiasi dei campi è vuoto entra nel blocco di errore.
     */
    if ($nome === '' || $cognome === '' || $dataIscrizione === '' || $password === '') {
        $msg     = 'Tutti i campi sono obbligatori.';
        $msgTipo = 'error';

        /*
         * Controllo 2: corrispondenza password
         * ─────────────────────────────────────────────────────────────────────
         * L'utente deve digitare la password due volte per confermarla.
         * Se le due stringhe non sono identiche, probabilmente ha fatto un
         * errore di digitazione: lo avvisiamo invece di salvare una password
         * che non conosce.
         *
         * Usiamo !== (confronto stretto) che verifica sia valore che tipo.
         */
    } elseif ($password !== $passwordConf) {
        $msg     = 'Le due password non coincidono. Riprova.';
        $msgTipo = 'error';

        /*
         * Controllo 3: lunghezza minima password
         * ─────────────────────────────────────────────────────────────────────
         * strlen() restituisce il numero di byte (caratteri ASCII) della stringa.
         * Imponiamo un minimo di 6 caratteri: una password più corta è troppo
         * facile da indovinare con attacchi a forza bruta.
         */
    } elseif (strlen($password) < 6) {
        $msg     = 'La password deve essere di almeno 6 caratteri.';
        $msgTipo = 'error';

    } else {

        /* ── Salvataggio ───────────────────────────────────────────────── */

        /*
         * Storage::aggiungiUtente()
         * ─────────────────────────────────────────────────────────────────
         * Delega il salvataggio alla classe Storage.
         * Internamente Storage si occupa di:
         *   - hashare la password con bcrypt
         *   - assegnare il numero tessera
         *   - salvare il record nel file JSON
         *
         * Restituisce il numero tessera (int) se tutto è andato bene,
         * oppure null se la scrittura del file è fallita.
         */
        $numeroTessera = Storage::aggiungiUtente($nome, $cognome, $dataIscrizione, $password);

        if ($numeroTessera !== null) {
            /*
             * htmlspecialchars() nel messaggio di successo
             * ─────────────────────────────────────────────────────────────
             * Il nome e cognome inseriti dall'utente vengono stampati
             * nel messaggio. È necessario proteggerli con htmlspecialchars()
             * per prevenire XSS: se un utente avesse inserito "<script>..."
             * nel campo nome, senza protezione verrebbe eseguito come codice.
             *
             * (int) davanti a $numeroTessera converte il valore a intero:
             * difesa aggiuntiva per assicurarsi che sia un numero puro.
             */
            $msg     = '✅ Utente <strong>' . htmlspecialchars($cognome . ' ' . $nome) . '</strong> registrato con successo! Tessera n° <strong>' . (int)$numeroTessera . '</strong>.';
            $msgTipo = 'success';

            /* Azzera le variabili del form: dopo il successo il form
               si svuota così l'operatore può registrare un altro utente */
            $nome = $cognome = '';
            $dataIscrizione = $defaultData;

        } else {
            /* Errore di scrittura file: la cartella data/ potrebbe non
               essere scrivibile dal processo PHP del server web */
            $msg     = 'Errore durante il salvataggio. Controlla che la cartella data/ sia scrivibile.';
            $msgTipo = 'error';
        }
    }
}

/*
 * $paginaCorrente
 * ─────────────────────────────────────────────────────────────────────────
 * Usata nel blocco navigazione per evidenziare il link attivo.
 * Vedi commento completo in index.php.
 */
$paginaCorrente = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione — Biblioteca Comunale</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>

<!-- ═══════════════════════════════════════════════════════════════════════
     HEADER / NAVIGAZIONE — inline, senza include
     Vedi commento completo in index.php.
     ═══════════════════════════════════════════════════════════════════════ -->
<header class="site-header">
    <div class="header-inner">
        <a href="index.php" class="logo">
            <span class="logo-icon">📚</span>
            <span class="logo-text">BIBLIO<em>TECA</em></span>
            <span class="logo-sub">Comunale</span>
        </a>
        <nav class="main-nav">
            <a class="nav-link <?= $paginaCorrente === 'index.php' ? 'active' : '' ?>"
               href="index.php"><span class="nav-icon">🏠</span> Home</a>
            <a class="nav-link <?= $paginaCorrente === 'registrazione.php' ? 'active' : '' ?>"
               href="registrazione.php"><span class="nav-icon">✍️</span> Registrazione</a>
            <a class="nav-link debug-link <?= $paginaCorrente === 'debug.php' ? 'active' : '' ?>"
               href="debug.php"><span class="nav-icon">🛠️</span> Debug</a>
        </nav>
    </div>
</header>

<main>

    <!-- ── HERO ─────────────────────────────────────────────────────────── -->
    <section class="page-hero">
        <div class="hero-badge">✍️ Iscriviti</div>
        <h1>Registrazione <span>Utente</span></h1>
        <p class="hero-sub">Compila il form per ottenere la tua tessera biblioteca</p>
    </section>

    <!-- ── FORM REGISTRAZIONE ────────────────────────────────────────────── -->
    <div class="card-single">

        <div class="card-header">
            <h2>Dati Utente</h2>
            <span class="card-badge">Tutti i campi sono obbligatori</span>
        </div>

        <?php
        /*
         * Blocco messaggio feedback
         * ─────────────────────────────────────────────────────────────────
         * Viene mostrato solo se $msg non è vuoto (cioè solo dopo un POST).
         * La classe CSS "$msgTipo" vale "success" o "error" e determina
         * il colore del box (verde o rosso) tramite il foglio di stile.
         *
         * Il contenuto di $msg viene stampato senza htmlspecialchars()
         * perché contiene HTML intenzionale (tag <strong>).
         * I valori dell'utente inclusi in $msg sono già stati protetti
         * con htmlspecialchars() quando $msg è stato costruito sopra.
         */
        if ($msg !== ''): ?>
            <div class="msg <?= $msgTipo ?>">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <!--
            FORM — Self-processing
            ─────────────────────────────────────────────────────────────────
            method="POST"  → i dati vengono inviati nel corpo della richiesta
                             HTTP, non nell'URL. Più sicuro di GET per dati
                             sensibili come le password.

            action="registrazione.php" → la destinazione del form è questa
                             stessa pagina. Al submit il browser invia una
                             richiesta POST a registrazione.php, che la
                             gestisce nel blocco if POST qui sopra.

            novalidate     → disabilita la validazione nativa del browser
                             per gestirla interamente lato server (PHP) e
                             con il nostro JavaScript personalizzato.
        -->
        <form method="POST" action="registrazione.php" novalidate>

            <!-- Riga nome + cognome affiancati con CSS flexbox/grid -->
            <div class="form-row">

                <div class="form-group">
                    <label for="nome">Nome</label>
                    <!--
                        value="<?= htmlspecialchars($nome) ?>"
                        ─────────────────────────────────────────────────────
                        Ripopola il campo con il valore già inserito in caso
                        di errore di validazione. In questo modo l'utente
                        vede cosa aveva scritto e corregge solo l'errore.
                        htmlspecialchars() protegge da XSS nell'attributo value.
                    -->
                    <input type="text" id="nome" name="nome"
                           placeholder="es. Mario" required maxlength="50"
                           value="<?= htmlspecialchars($nome) ?>">
                </div>

                <div class="form-group">
                    <label for="cognome">Cognome</label>
                    <input type="text" id="cognome" name="cognome"
                           placeholder="es. Rossi" required maxlength="50"
                           value="<?= htmlspecialchars($cognome) ?>">
                </div>

            </div>

            <!-- Campo data iscrizione -->
            <div class="form-group">
                <label for="data_iscrizione">Data di Iscrizione</label>
                <!--
                    type="date" → mostra un date picker nel browser.
                    Il valore deve essere nel formato Y-m-d (es. 2026-03-26):
                    è il formato ISO 8601 richiesto dall'HTML standard,
                    indipendentemente da come il browser lo visualizza all'utente.
                    Di default usiamo $dataIscrizione che vale la data di oggi.
                -->
                <input type="date" id="data_iscrizione" name="data_iscrizione"
                       required value="<?= htmlspecialchars($dataIscrizione) ?>">
                <small class="field-hint">Di default viene inserita la data odierna</small>
            </div>

            <!-- Riga password + conferma affiancate -->
            <div class="form-row">

                <div class="form-group">
                    <label for="password">Password</label>
                    <!--
                        type="password" → nasconde i caratteri digitati
                        sostituendoli con punti o asterischi nel browser.
                        I campi password NON vengono mai ripopolati (nessun
                        attributo value): è una pratica di sicurezza standard,
                        le password non devono mai riapparire in chiaro.
                        minlength="6" → validazione HTML5 lato client
                        (affiancata dalla validazione PHP lato server).
                    -->
                    <input type="password" id="password" name="password"
                           placeholder="Minimo 6 caratteri" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_conf">Conferma Password</label>
                    <input type="password" id="password_conf" name="password_conf"
                           placeholder="Ripeti la password" required>
                </div>

            </div>

            <!-- Nota informativa sul numero tessera -->
            <div class="info-note">
                <span>ℹ️</span>
                <span>Il numero di tessera verrà assegnato automaticamente al momento della registrazione.</span>
            </div>

            <!-- Bottone di invio del form -->
            <button type="submit" class="btn btn-primary">REGISTRA UTENTE →</button>

        </form>
    </div>

    <!-- Link alla pagina di debug -->
    <div class="cta-classifica">
        <a href="debug.php" class="btn btn-secondary">🛠️ VISUALIZZA TUTTI GLI UTENTI (DEBUG)</a>
    </div>

</main>

<script>
    /**
     * Validazione client-side — corrispondenza password
     * ──────────────────────────────────────────────────────────────────────────
     * Controlla in tempo reale che i due campi password coincidano.
     * Questo è un feedback immediato per l'utente: senza aspettare il submit
     * e il round-trip al server, vede subito se c'è un problema.
     *
     * IMPORTANTE: questa validazione è un'aggiunta di comodità, NON un
     * sostituto della validazione PHP. JavaScript può essere disabilitato
     * o aggirato, quindi PHP ricontrolla tutto lato server.
     *
     * L'intera funzione è avvolta in un IIFE (Immediately Invoked Function
     * Expression): (function() { ... }()) si auto-esegue subito.
     * Questo crea uno "scope privato": le variabili pwd e pwdConf non
     * inquinano il namespace globale di JavaScript.
     */
    (function () {

        /*
         * document.getElementById()
         * ─────────────────────────────────────────────────────────────────────
         * Recupera i riferimenti ai due elementi <input> password nel DOM,
         * cercandoli per il loro attributo id.
         * Salvandoli in variabili evitare di ricercarli nel DOM ad ogni evento.
         */
        const pwd     = document.getElementById('password');
        const pwdConf = document.getElementById('password_conf');

        /**
         * verificaPassword()
         * ─────────────────────────────────────────────────────────────────────
         * Confronta i valori dei due campi password.
         *
         * setCustomValidity() è un metodo nativo dell'API Constraint Validation
         * del browser:
         *   - setCustomValidity('')       → marca il campo come VALIDO
         *   - setCustomValidity('msg...')  → marca il campo come NON VALIDO
         *                                   con il messaggio specificato.
         *                                   Il browser mostrerà questo messaggio
         *                                   nel tooltip di errore al submit.
         *
         * pwd.value e pwdConf.value sono le stringhe attualmente nei campi.
         */
        function verificaPassword() {
            if (pwd.value !== pwdConf.value) {
                /* Le password non coincidono: segna il secondo campo come invalido */
                pwdConf.setCustomValidity('Le due password non coincidono.');
            } else {
                /* Coincidono: rimuove l'errore */
                pwdConf.setCustomValidity('');
            }
        }

        /*
         * addEventListener('input', callback)
         * ─────────────────────────────────────────────────────────────────────
         * Registra la funzione verificaPassword come listener per l'evento
         * 'input' su entrambi i campi.
         *
         * L'evento 'input' si attiva ogni volta che il valore del campo cambia
         * (ad ogni tasto premuto), offrendo feedback in tempo reale.
         * Lo aggiungiamo a entrambi i campi: se l'utente modifica la password
         * originale dopo aver già scritto la conferma, il controllo si riesegue.
         */
        pwd.addEventListener('input',     verificaPassword);
        pwdConf.addEventListener('input', verificaPassword);

    }()); /* fine IIFE — si esegue immediatamente */
</script>

</body>
</html>