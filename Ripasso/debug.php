<?php
/**
 * debug.php — Pagina di Debug
 * ⚠️ Solo per sviluppatori / ambiente di test.
 */

require_once 'storage.php';

$utenti       = Storage::utentiOrdinati();
$totaleUtenti = count($utenti);
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
        <div class="hero-badge debug-badge">🛠️ Solo Debug</div>
        <h1>Utenti <span>Registrati</span></h1>
        <p class="hero-sub">Visualizzazione dati a scopo di sviluppo — non usare in produzione</p>
    </section>

    <!-- ── BANNER DI AVVISO ───────────────────────────────────────── -->
    <div class="debug-warning">
        <span class="debug-warning-icon">⚠️</span>
        <div>
            <strong>Attenzione — Pagina di Debug</strong><br>
            Questa pagina è visibile solo in ambiente di sviluppo e deve essere rimossa o protetta prima di andare in produzione.
        </div>
    </div>

    <!-- ── STATISTICHE RAPIDE ─────────────────────────────────────── -->
    <div class="stats-bar">
        <div class="stat-item">
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

    <!-- ── TABELLA UTENTI ─────────────────────────────────────────── -->
    <div class="card-single">

        <div class="card-header">
            <h2>Tabella Utenti</h2>
            <span class="card-badge"><?= $totaleUtenti ?> record</span>
        </div>

        <?php if ($totaleUtenti === 0): ?>
            <div class="msg info">
                Nessun utente registrato. <a href="registrazione.php">Registra il primo utente →</a>
            </div>

        <?php else: ?>
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
                    <?php foreach ($utenti as $utente): ?>
                        <tr>
                            <td class="td-tessera">
                                <span class="tessera-badge">#<?= (int)$utente['numero_tessera'] ?></span>
                            </td>
                            <td><?= htmlspecialchars($utente['cognome']) ?></td>
                            <td><?= htmlspecialchars($utente['nome']) ?></td>
                            <td>
                                <?php
                                $ts = strtotime($utente['data_iscrizione']);
                                echo $ts !== false
                                        ? date('d/m/Y', $ts)
                                        : htmlspecialchars($utente['data_iscrizione']);
                                ?>
                            </td>
                            <td class="td-password">
                                <span class="pwd-hidden" title="Password non visibile per sicurezza">••••••••</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                Totale: <strong><?= $totaleUtenti ?></strong>
                utent<?= $totaleUtenti === 1 ? 'e' : 'i' ?>
                registrat<?= $totaleUtenti === 1 ? 'o' : 'i' ?>
                &nbsp;·&nbsp; Sorgente: <code>data/utenti.json</code>
            </div>
        <?php endif; ?>

    </div>

    <!-- ── AZIONI ─────────────────────────────────────────────────── -->
    <div class="cta-classifica">
        <a href="registrazione.php" class="btn btn-primary">✍️ REGISTRA NUOVO UTENTE</a>
        <a href="index.php"         class="btn btn-secondary">🏠 TORNA ALLA HOME</a>
    </div>

</main>

</body>
</html>