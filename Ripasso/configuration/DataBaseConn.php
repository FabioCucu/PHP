<?php
/**
 * DataBaseConn.php
 * ──────────────────────────────────────────────────────────────────────────
 * Classe Singleton per la gestione della connessione al database.
 *
 * Il pattern Singleton garantisce che durante l'intera esecuzione dello
 * script esista una sola istanza della connessione PDO, evitando di aprire
 * più connessioni inutili al database.
 * ──────────────────────────────────────────────────────────────────────────
 */
class DataBaseConn
{
    /**
     * @var PDO|null $db
     * Proprietà statica privata: conserva l'unica istanza PDO dell'intera
     * applicazione. È null finché non viene chiamato getDB() per la prima volta.
     */
    private static ?PDO $db = null;

    /**
     * getDB()
     * ──────────────────────────────────────────────────────────────────────
     * Metodo statico che restituisce la connessione PDO.
     * - Se la connessione NON esiste ancora, la crea usando i parametri
     *   dell'array $dbconfig passato come argomento.
     * - Se la connessione esiste già, restituisce quella già aperta
     *   (pattern Singleton).
     *
     * @param  array    $dbconfig  Array di configurazione (da DBConf.php)
     *                             con chiavi: dsn, username, password, options
     * @return PDO|null            Oggetto PDO oppure null in caso di errore
     */
    public static function getDB(array $dbconfig): ?PDO
    {
        /* Controlla se la connessione è già stata creata in precedenza */
        if (!isset(self::$db)) {
            try {
                /*
                 * Crea una nuova istanza PDO con:
                 * - DSN: identifica driver, host, porta, db, charset
                 * - username e password: credenziali di accesso
                 * - options: configurazioni aggiuntive (modalità errore, fetch, ecc.)
                 */
                self::$db = new PDO(
                    $dbconfig["dsn"],
                    $dbconfig["username"],
                    $dbconfig["password"],
                    $dbconfig["options"]
                );
            } catch (PDOException $e) {
                /*
                 * In caso di errore di connessione, impostiamo $db a null
                 * e stampiamo un messaggio di errore generico.
                 * In produzione evitare di mostrare $e->getMessage() all'utente.
                 */
                self::$db = null;
                error_log("Errore di connessione DB: " . $e->getMessage());
            }
        }

        /* Restituisce la connessione (o null se la connessione è fallita) */
        return self::$db;
    }
}
