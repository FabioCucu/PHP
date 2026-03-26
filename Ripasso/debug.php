<?php
/**
 * debug.php — Pagina di Debug
 * ──────────────────────────────────────────────────────────────────────────
 * SOLO PER SVILUPPATORI / SOLO IN AMBIENTE DI TEST.
 *
 * Mostra in forma tabellare tutti gli utenti salvati nel file JSON.
 * La password NON viene mai mostrata, nemmeno come hash.
 *
 * ⚠️  ATTENZIONE: In produzione questa pagina deve essere rimossa
 *     o protetta da autenticazione prima del deploy.
 * ──────────────────────────────────────────────────────────────────────────
 */

/*
 * require_once 'storage.php'
 * ─────────────────────────────────────────────────────────────────────────
 * Include la classe Storage che gestisce la lettura del file JSON.
 * require_once garantisce che il file sia incluso una sola volta e genera
 * un errore fatale se non trovato (comportamento corretto: senza Storage
 * questa pagina non può funzionare).
 */
require_once 'storage.php';

/*
 * Storage::utentiOrdinati()
 * ─────────────────────────────────────────────────────────────────────────
 * Chiama il metodo statico della classe Storage che:
 *   1. Legge il file data/utenti.json
 *   2. Ordina i record alfabeticamente per cognome, poi per nome
 *   3. Restituisce l'array ordinato
 *
 * Se il file non esiste ancora (nessun utente registrato), il metodo
 * restituisce un array vuoto [] e la pagina mostra il messaggio "Nessun utente".
 */
$utenti = Storage::utentiOrdinati();

/*
 * count($utenti)
 * ─────────────────────────────────────────────────────────────────────────
 * Conta il numero di elementi dell'array $utenti.
 * Usato per mostrare il totale nelle statistiche e nel footer della tabella.
 */
$totaleUtenti = count($utenti);

/*
 * $paginaCorrente
 * ─────────────────────────────────────────────────────────────────────────
 * Nome del file corrente, usato nel nav per evidenziare il link attivo.
 * Vedi commento completo in index.php.
 */
$paginaCorrente = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug — Utenti Registrati — Biblioteca</title>
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

    <!-- ── HERO ──────────────────────────────────────────────────────────── -->
    <section class="page-hero">
        <div class="hero-badge debug-badge">🛠️ Solo Debug</div>
        <h1>Utenti <span>Registrati</span></h1>
        <p class="hero-sub">Visualizzazione dati a scopo di sviluppo — non usare in produzione</p>
    </section>

    <!-- ── BANNER DI AVVISO ──────────────────────────────────────────────── -->
    <div class="debug-warning">
        <span class="debug-warning-icon">⚠️</span>
        <div>
            <strong>Attenzione — Pagina di Debug</strong><br>
            Questa pagina è visibile solo in ambiente di sviluppo e deve essere rimossa o protetta prima di andare in produzione.
        </div>
    </div>

    <!-- ── STATISTICHE RAPIDE ────────────────────────────────────────────── -->
    <div class="stats-bar">

        <div class="stat-item">
            <!--
                <?= $totaleUtenti ?>
                Forma abbreviata di <?php echo $totaleUtenti; ?>
                Stampa il numero totale di utenti calcolato da count() sopra.
                Non serve htmlspecialchars() perché è un intero generato da PHP,
                non un input dell'utente.
            -->
            <span class="stat-number"><?= $totaleUtenti ?></span>
            <span class="stat-label">Utenti registrati</span>
        </div>

        <div class="stat-item">
            <span class="stat-number">4</span>
            <span class="stat-label">Campi visibili</span>
        </div>

        <div class="stat-item">
            <span class="stat-number">🔒</span>
            <span class="stat-label">Password nascosta</span>
        </div>

        <div class="stat-item">
            <span class="stat-number">📄</span>
            <span class="stat-label">Storage: JSON</span>
        </div>

    </div>

    <!-- ── TABELLA UTENTI ────────────────────────────────────────────────── -->
    <div class="card-single">

        <div class="card-header">
            <h2>Tabella Utenti</h2>
            <span class="card-badge"><?= $totaleUtenti ?> record</span>
        </div>

        <?php
        /*
         * Struttura condizionale PHP "alternativa"
         * ─────────────────────────────────────────────────────────────────
         * if ... : / elseif ... : / else: / endif;
         * È la sintassi alternativa di PHP per mescolare blocchi di codice
         * con HTML in modo più leggibile rispetto alle parentesi graffe.
         * Funziona esattamente come if { } else { } ma è più chiara
         * quando il contenuto del blocco è HTML.
         *
         * Mostriamo tre casi:
         *   1. Nessun utente          → messaggio con link a registrazione
         *   2. Utenti presenti        → tabella con i dati
         */
        if ($totaleUtenti === 0): ?>

            <!--
                Caso 1: nessun utente nel file JSON.
                Il messaggio contiene un link diretto alla pagina di registrazione.
            -->
            <div class="msg info">
                Nessun utente registrato. <a href="registrazione.php">Registra il primo utente →</a>
            </div>

        <?php else: ?>

            <!--
                Caso 2: ci sono utenti, mostriamo la tabella.
                Il div "table-wrapper" permette lo scroll orizzontale su schermi piccoli.
            -->
            <div class="table-wrapper">
                <table class="debug-table">
                    <thead>
                    <tr>
                        <th>N° Tessera</th>
                        <th>Cognome</th>
                        <th>Nome</th>
                        <th>Data Iscrizione</th>
                        <th>Password</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*
                     * foreach ($utenti as $utente)
                     * ─────────────────────────────────────────────────
                     * Itera sull'array $utenti. Ad ogni iterazione
                     * $utente è un array associativo con le chiavi:
                     *   'numero_tessera', 'nome', 'cognome',
                     *   'data_iscrizione', 'password_hash'
                     *
                     * Per ogni utente generiamo una riga <tr> della tabella.
                     * La sintassi alternativa foreach ... : / endforeach;
                     * è più leggibile quando il corpo del ciclo contiene HTML.
                     */
                    foreach ($utenti as $utente): ?>
                        <tr>

                            <!-- Numero tessera -->
                            <td class="td-tessera">
                                <!--
                                    (int) converte il valore a intero prima di stamparlo.
                                    Difesa extra: anche se nel JSON fosse finita una stringa,
                                    verrebbe convertita a 0 invece di essere stampata come testo.
                                -->
                                <span class="tessera-badge">#<?= (int)$utente['numero_tessera'] ?></span>
                            </td>

                            <!-- Cognome — htmlspecialchars() protegge da XSS -->
                            <td><?= htmlspecialchars($utente['cognome']) ?></td>

                            <!-- Nome — htmlspecialchars() protegge da XSS -->
                            <td><?= htmlspecialchars($utente['nome']) ?></td>

                            <!-- Data di iscrizione -->
                            <td>
                                <?php
                                /*
                                 * Conversione formato data per la visualizzazione
                                 * ─────────────────────────────────────────────────
                                 * Il file JSON salva la data nel formato Y-m-d ("2026-03-26")
                                 * perché è il formato standard ISO e quello richiesto da
                                 * input type="date". Ma per mostrare la data in italiano
                                 * vogliamo il formato d/m/Y ("26/03/2026").
                                 *
                                 * strtotime($stringa)
                                 *   → converte una stringa data/ora in un timestamp Unix
                                 *     (numero di secondi dall'1/1/1970).
                                 *     Restituisce false se la stringa non è riconoscibile.
                                 *
                                 * date('d/m/Y', $timestamp)
                                 *   → riconverte il timestamp nel formato italiano.
                                 *
                                 * Il controllo $ts !== false evita di passare false a date(),
                                 * che genererebbe un errore. In quel caso mostriamo la data
                                 * originale dal JSON (meglio di niente).
                                 */
                                $ts = strtotime($utente['data_iscrizione']);
                                echo $ts !== false
                                        ? date('d/m/Y', $ts)
                                        : htmlspecialchars($utente['data_iscrizione']);
                                ?>
                            </td>

                            <!-- Password: NON mostrata per sicurezza -->
                            <td class="td-password">
                                <!--
                                    Mostriamo solo punti come indicatore visivo
                                    che il campo esiste ma è protetto.
                                    Non mostriamo nemmeno l'hash bcrypt:
                                    anche se non è la password originale,
                                    è comunque un'informazione sensibile.
                                -->
                                <span class="pwd-hidden" title="Password non visibile per sicurezza">••••••••</span>
                            </td>

                        </tr>
                    <?php endforeach; /* fine ciclo utenti */ ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer della tabella con conteggio e sorgente dati -->
            <div class="table-footer">
                <!--
                    Plurale dinamico in italiano:
                    - $totaleUtenti === 1 → "utente registrato"
                    - altrimenti          → "utenti registrati"

                    L'operatore ternario scelto per ogni suffisso:
                      $totaleUtenti === 1 ? 'e' : 'i'   → utent(e/i)
                      $totaleUtenti === 1 ? 'o' : 'i'   → registrat(o/i)
                -->
                Totale: <strong><?= $totaleUtenti ?></strong>
                utent<?= $totaleUtenti === 1 ? 'e' : 'i' ?>
                registrat<?= $totaleUtenti === 1 ? 'o' : 'i' ?>
                &nbsp;·&nbsp; Sorgente: <code>data/utenti.json</code>
            </div>

        <?php endif; /* fine condizione utenti */ ?>

    </div>

    <!-- ── AZIONI ────────────────────────────────────────────────────────── -->
    <div class="cta-classifica">
        <a href="registrazione.php" class="btn btn-primary">✍️ REGISTRA NUOVO UTENTE</a>
        <a href="index.php"         class="btn btn-secondary">🏠 TORNA ALLA HOME</a>
    </div>

</main>

</body>
</html>