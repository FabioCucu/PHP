<!-- ============================================================
     nav.php
     ──────────────────────────────────────────────────────────
     Componente di navigazione comune a tutte le pagine.
     Viene incluso tramite <?php include "nav.php"; ?> in ogni
     pagina dell'applicativo.

     La classe CSS "active" viene aggiunta dinamicamente al link
     corrispondente alla pagina corrente, usando basename() per
     estrarre solo il nome del file dall'URL.
     ============================================================ -->

<header class="site-header">
    <div class="header-inner">

        <!-- Logo / Brand -->
        <a href="index.php" class="logo">
            <span class="logo-icon">📚</span>
            <span class="logo-text">BIBLIO<em>TECA</em></span>
            <span class="logo-sub">Comunale</span>
        </a>

        <!-- Navigazione principale -->
        <nav class="main-nav">

            <!--
                Link alla Home: attivo se il file corrente è index.php.
                basename($_SERVER['PHP_SELF']) restituisce il nome del file
                PHP attualmente in esecuzione (es. "index.php").
            -->
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>"
               href="index.php">
                <span class="nav-icon">🏠</span> Home
            </a>

            <!--
                Link alla pagina di registrazione: attivo se il file
                corrente è registrazione.php.
            -->
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'registrazione.php' ? 'active' : '' ?>"
               href="registrazione.php">
                <span class="nav-icon">✍️</span> Registrazione
            </a>

            <!--
                Link alla pagina di debug (solo per sviluppatori):
                attivo se il file corrente è debug.php.
            -->
            <a class="nav-link debug-link <?= basename($_SERVER['PHP_SELF']) === 'debug.php' ? 'active' : '' ?>"
               href="debug.php">
                <span class="nav-icon">🛠️</span> Debug
            </a>

        </nav>
    </div>
</header>
