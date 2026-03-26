<?php
/**
 * index.php — Home Page
 * Biblioteca Comunale
 */
date_default_timezone_set('Europe/Rome');

$dataOggi = date('d/m/Y');
$oraOra   = date('H:i:s');

$giorniIta = [
        1 => 'Lunedì', 2 => 'Martedì', 3 => 'Mercoledì',
        4 => 'Giovedì', 5 => 'Venerdì', 6 => 'Sabato', 7 => 'Domenica'
];
$mesiIta = [
        1 => 'gennaio', 2 => 'febbraio', 3 => 'marzo',
        4 => 'aprile',  5 => 'maggio',   6 => 'giugno',
        7 => 'luglio',  8 => 'agosto',   9 => 'settembre',
        10 => 'ottobre', 11 => 'novembre', 12 => 'dicembre'
];
$dataEstesa = $giorniIta[(int)date('N')] . ', ' . date('j') . ' ' . $mesiIta[(int)date('n')] . ' ' . date('Y');

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
        <div class="hero-badge">🏛️ Biblioteca Comunale</div>
        <h1>Benvenuto nella<br><span>Biblioteca</span></h1>
        <p class="hero-sub">Il luogo dove ogni storia trova casa e ogni lettore trova la sua storia.</p>
    </section>

    <!-- ── OROLOGIO / DATA ────────────────────────────────────────── -->
    <section class="clock-section">
        <div class="clock-card">
            <div class="clock-date"><?= htmlspecialchars($dataEstesa) ?></div>
            <div class="clock-time" id="clock"><?= htmlspecialchars($oraOra) ?></div>
            <div class="clock-label">Ora locale — Europa/Roma</div>
        </div>
    </section>

    <!-- ── CARDS INFORMATIVE ──────────────────────────────────────── -->
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

        <div class="info-card cta-card">
            <div class="info-icon">🪪</div>
            <h3>Vuoi iscriverti?</h3>
            <p>Registrati gratuitamente e ottieni la tua tessera per prendere libri in prestito.</p>
            <a href="registrazione.php" class="btn btn-primary">ISCRIVITI ORA →</a>
        </div>

    </section>

</main>

<script>
    setInterval(function () {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        document.getElementById('clock').textContent =
            pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
    }, 1000);
</script>

</body>
</html>