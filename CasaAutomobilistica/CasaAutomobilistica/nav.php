<!-- Header Generale -->

<header>
    <div class="header-inner">
        <div class="logo">
            <span class="logo-flag">🏁</span>
            <span class="logo-text">GRAND<em>PRIX</em></span>
        </div>
        <nav>
            <a class="nav-btn <?= basename($_SERVER['PHP_SELF']) === 'index.php'      ? 'active' : '' ?>" href="index.php">GARA</a>
            <a class="nav-btn <?= basename($_SERVER['PHP_SELF']) === 'iscrizione.php' ? 'active' : '' ?>" href="iscrizione.php">ISCRIZIONE</a>
            <a class="nav-btn <?= basename($_SERVER['PHP_SELF']) === 'classifica.php'      ? 'active' : '' ?>" href="classifica.php">CLASSIFICA</a>
            <a class="nav-btn <?= basename($_SERVER['PHP_SELF']) === 'partecipazione.php'      ? 'active' : '' ?>" href="partecipazione.php">PARTECIPAZIONE</a>
        </nav>
    </div>
</header>