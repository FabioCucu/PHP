<?php
/**
 * registrazione.php — Registrazione Utenti
 * Biblioteca Comunale
 *
 * Flusso:
 *   GET  → mostra il form vuoto
 *   POST → valida i dati, salva su file JSON, mostra esito
 */

require_once 'storage.php';

date_default_timezone_set('Europe/Rome');

$defaultData = date('Y-m-d');

$msg     = '';
$msgTipo = '';

/* Variabili form (per ripopolare in caso di errore) */
$nome           = '';
$cognome        = '';
$dataIscrizione = $defaultData;

/* ── Gestione POST ──────────────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome           = trim($_POST['nome']           ?? '');
    $cognome        = trim($_POST['cognome']         ?? '');
    $dataIscrizione = trim($_POST['data_iscrizione'] ?? $defaultData);
    $password       = trim($_POST['password']        ?? '');
    $passwordConf   = trim($_POST['password_conf']   ?? '');

    if ($nome === '' || $cognome === '' || $dataIscrizione === '' || $password === '') {
        $msg     = 'Tutti i campi sono obbligatori.';
        $msgTipo = 'error';

    } elseif ($password !== $passwordConf) {
        $msg     = 'Le due password non coincidono. Riprova.';
        $msgTipo = 'error';

    } elseif (strlen($password) < 6) {
        $msg     = 'La password deve essere di almeno 6 caratteri.';
        $msgTipo = 'error';

    } else {
        $numeroTessera = Storage::aggiungiUtente($nome, $cognome, $dataIscrizione, $password);

        if ($numeroTessera !== null) {
            $msg     = '✅ Utente <strong>' . htmlspecialchars($cognome . ' ' . $nome) . '</strong> registrato con successo! Tessera n° <strong>' . $numeroTessera . '</strong>.';
            $msgTipo = 'success';
            /* Azzera i campi dopo il successo */
            $nome = $cognome = '';
            $dataIscrizione = $defaultData;
        } else {
            $msg     = 'Errore durante il salvataggio. Controlla che la cartella data/ sia scrivibile.';
            $msgTipo = 'error';
        }
    }
}

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

<!-- ═══════════════════════════════════════════════════════════════
     HEADER / NAV — inline, nessun include
     ═══════════════════════════════════════════════════════════════ -->
<header class="site-header">
    <div class="header-inner">
        <a href="index.php" class="logo">
            <span class="logo-icon">📚</span>
            <span class="logo-text">BIBLIO<em>TECA</em></span>
            <span class="logo-sub">Comunale</span>
        </a>
        <nav class="main-nav">
            <a class="nav-link <?= $paginaCorrente === 'index.php'         ? 'active' : '' ?>" href="index.php">
                <span class="nav-icon">🏠</span> Home
            </a>
            <a class="nav-link <?= $paginaCorrente === 'registrazione.php' ? 'active' : '' ?>" href="registrazione.php">
                <span class="nav-icon">✍️</span> Registrazione
            </a>
            <a class="nav-link debug-link <?= $paginaCorrente === 'debug.php' ? 'active' : '' ?>" href="debug.php">
                <span class="nav-icon">🛠️</span> Debug
            </a>
        </nav>
    </div>
</header>

<main>

    <!-- ── HERO ───────────────────────────────────────────────────── -->
    <section class="page-hero">
        <div class="hero-badge">✍️ Iscriviti</div>
        <h1>Registrazione <span>Utente</span></h1>
        <p class="hero-sub">Compila il form per ottenere la tua tessera biblioteca</p>
    </section>

    <!-- ── FORM REGISTRAZIONE ─────────────────────────────────────── -->
    <div class="card-single">

        <div class="card-header">
            <h2>Dati Utente</h2>
            <span class="card-badge">Tutti i campi sono obbligatori</span>
        </div>

        <?php if ($msg !== ''): ?>
            <div class="msg <?= $msgTipo ?>">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="registrazione.php" novalidate>

            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Nome</label>
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

            <div class="form-group">
                <label for="data_iscrizione">Data di Iscrizione</label>
                <input type="date" id="data_iscrizione" name="data_iscrizione"
                       required value="<?= htmlspecialchars($dataIscrizione) ?>">
                <small class="field-hint">Di default viene inserita la data odierna</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="Minimo 6 caratteri" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="password_conf">Conferma Password</label>
                    <input type="password" id="password_conf" name="password_conf"
                           placeholder="Ripeti la password" required>
                </div>
            </div>

            <div class="info-note">
                <span>ℹ️</span>
                <span>Il numero di tessera verrà assegnato automaticamente al momento della registrazione.</span>
            </div>

            <button type="submit" class="btn btn-primary">REGISTRA UTENTE →</button>

        </form>
    </div>

    <div class="cta-classifica">
        <a href="debug.php" class="btn btn-secondary">🛠️ VISUALIZZA TUTTI GLI UTENTI (DEBUG)</a>
    </div>

</main>

<script>
    (function () {
        const pwd     = document.getElementById('password');
        const pwdConf = document.getElementById('password_conf');
        function verificaPassword() {
            pwdConf.setCustomValidity(
                pwd.value !== pwdConf.value ? 'Le due password non coincidono.' : ''
            );
        }
        pwd.addEventListener('input',     verificaPassword);
        pwdConf.addEventListener('input', verificaPassword);
    }());
</script>

</body>
</html>