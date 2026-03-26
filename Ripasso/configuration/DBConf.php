<?php
/**
 * DBConf.php
 * ──────────────────────────────────────────────────────────────────────────
 * File di configurazione del database.
 * Restituisce un array associativo con i parametri necessari per la
 * connessione PDO al database MySQL della biblioteca comunale.
 *
 * NOTA: in produzione non inserire mai credenziali in chiaro nel codice.
 *       Usare variabili d'ambiente o file .env esclusi dal versioning.
 * ──────────────────────────────────────────────────────────────────────────
 */
return [
    /*
     * DSN (Data Source Name):
     * Stringa che identifica il driver, l'host, la porta, il nome del
     * database e il charset da usare.
     * Formato: "driver:host=HOST;port=PORTA;dbname=NOME_DB;charset=CHARSET"
     */
    "dsn"      => "mysql:host=127.0.0.1;port=3306;dbname=biblioteca_comunale;charset=utf8mb4",

    /*
     * Credenziali di accesso al database.
     * Modificare con utente e password reali per l'ambiente di produzione.
     */
    "username" => "root",
    "password" => "",

    /*
     * Opzioni PDO:
     * - FETCH_ASSOC  → i record vengono restituiti come array associativi
     * - ERRMODE_EXCEPTION → gli errori SQL lanciano eccezioni PDOException
     *                        (facilita il debug)
     */
    "options"  => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    ]
];
