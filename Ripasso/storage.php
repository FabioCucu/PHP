<?php
/**
 * storage.php
 * ──────────────────────────────────────────────────────────────────────────
 * Livello di persistenza basato su file JSON.
 * Sostituisce completamente il database MySQL.
 *
 * Tutti i dati degli utenti vengono salvati in un singolo file:
 *   data/utenti.json
 *
 * Struttura di ogni record utente nel JSON:
 * {
 *     "numero_tessera":  1,
 *     "nome":            "Mario",
 *     "cognome":         "Rossi",
 *     "data_iscrizione": "2026-03-26",
 *     "password_hash":   "$2y$10$..."
 * }
 *
 * La classe è composta da soli metodi statici: non serve mai creare
 * un'istanza con "new Storage()", si chiama direttamente Storage::metodo().
 * ──────────────────────────────────────────────────────────────────────────
 */
class Storage
{
    /**
     * $file — percorso assoluto del file JSON di storage
     * ─────────────────────────────────────────────────────────────────────
     * È una proprietà statica privata: esiste una sola copia condivisa
     * da tutti i metodi della classe, non modificabile dall'esterno.
     *
     * __DIR__ è una costante magica di PHP che contiene il percorso
     * assoluto della CARTELLA in cui si trova questo file (storage.php).
     * Usare __DIR__ invece di un percorso relativo come './data/utenti.json'
     * garantisce che il file venga trovato correttamente indipendentemente
     * da quale cartella sia la "working directory" del processo PHP.
     *
     * Esempio: se storage.php è in /var/www/biblioteca/,
     * allora $file vale /var/www/biblioteca/data/utenti.json
     */
    private static string $file = __DIR__ . '/data/utenti.json';

    /**
     * init()
     * ─────────────────────────────────────────────────────────────────────
     * Metodo privato di inizializzazione: garantisce che la cartella
     * data/ esista prima di qualsiasi operazione di lettura o scrittura.
     *
     * Viene chiamato all'inizio di ogni metodo pubblico come "guardia".
     *
     * dirname(self::$file)
     *   → estrae solo la parte cartella dal percorso completo del file.
     *     Es: "/var/www/biblioteca/data/utenti.json" → "/var/www/biblioteca/data"
     *
     * is_dir($dir)
     *   → restituisce true se il percorso esiste ed è una cartella.
     *
     * mkdir($dir, 0755, true)
     *   → crea la cartella.
     *     0755 = permessi Unix: il proprietario può leggere/scrivere/eseguire,
     *            gli altri possono solo leggere ed eseguire.
     *     true = crea anche le cartelle intermedie mancanti (come mkdir -p).
     *
     * @return void
     */
    private static function init(): void
    {
        $dir = dirname(self::$file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * leggiUtenti()
     * ─────────────────────────────────────────────────────────────────────
     * Legge il file JSON e restituisce tutti gli utenti come array PHP.
     * Se il file non esiste ancora (primo avvio), restituisce un array vuoto.
     *
     * file_exists(self::$file)
     *   → controlla se il file esiste sul disco prima di aprirlo.
     *     Senza questo controllo, file_get_contents() genererebbe un warning.
     *
     * file_get_contents(self::$file)
     *   → legge l'intero contenuto del file come stringa.
     *     È adatto per file di piccole/medie dimensioni come il nostro JSON.
     *
     * json_decode($json, true)
     *   → converte la stringa JSON in una struttura PHP.
     *     Il secondo argomento "true" forza la restituzione di array associativi
     *     invece di oggetti stdClass. Con true ogni { } diventa un array [].
     *
     * is_array($data) ? $data : []
     *   → controllo difensivo: se json_decode fallisce (JSON corrotto)
     *     restituisce [] invece di null, evitando errori nei foreach.
     *
     * @return array<int, array<string, mixed>>  Array di record utente
     */
    public static function leggiUtenti(): array
    {
        /* Assicura che la cartella data/ esista */
        self::init();

        /* Se il file non esiste ancora, non c'è nessun utente da leggere */
        if (!file_exists(self::$file)) {
            return [];
        }

        /* Legge il contenuto grezzo del file */
        $json = file_get_contents(self::$file);

        /* Decodifica il JSON in array PHP */
        $data = json_decode($json, true);

        /* Restituisce l'array o [] in caso di JSON non valido */
        return is_array($data) ? $data : [];
    }

    /**
     * scriviUtenti()
     * ─────────────────────────────────────────────────────────────────────
     * Serializza l'array degli utenti in JSON e lo salva sul disco.
     * È privato: solo i metodi di questa classe possono chiamarlo.
     *
     * json_encode($utenti, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
     *   → converte l'array PHP in stringa JSON.
     *     JSON_PRETTY_PRINT       → formatta il JSON con indentazione e
     *                               ritorni a capo, rendendolo leggibile
     *                               da un editor di testo.
     *     JSON_UNESCAPED_UNICODE  → salva i caratteri speciali (àèìòù, emoji)
     *                               così come sono, invece di convertirli in
     *                               sequenze \uXXXX.
     *     Il '|' è l'operatore OR bitwise: combina i due flag in uno.
     *
     * file_put_contents($file, $json, LOCK_EX)
     *   → scrive la stringa $json nel file, sostituendo il contenuto esistente.
     *     LOCK_EX (Exclusive Lock) = blocca il file durante la scrittura,
     *     impedendo che due richieste PHP simultanee corrompano il file
     *     scrivendoci sopra contemporaneamente.
     *     Restituisce il numero di byte scritti oppure false in caso di errore.
     *
     * !== false
     *   → controlla che la scrittura sia riuscita. Restituiamo bool.
     *
     * @param  array $utenti  Array completo degli utenti da salvare
     * @return bool           true se il salvataggio è riuscito, false altrimenti
     */
    private static function scriviUtenti(array $utenti): bool
    {
        self::init();
        $json = json_encode($utenti, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents(self::$file, $json, LOCK_EX) !== false;
    }

    /**
     * aggiungiUtente()
     * ─────────────────────────────────────────────────────────────────────
     * Aggiunge un nuovo utente al file JSON e restituisce il numero di
     * tessera assegnato automaticamente (come AUTO_INCREMENT del database).
     *
     * Flusso:
     *   1. Legge tutti gli utenti esistenti
     *   2. Calcola il prossimo numero tessera disponibile
     *   3. Costruisce il record del nuovo utente (con password hashata)
     *   4. Appende il nuovo utente all'array
     *   5. Riscrive l'intero file JSON
     *   6. Restituisce il numero tessera o null se la scrittura fallisce
     *
     * Parametri:
     * ─────────────────────────────────────────────────────────────────────
     * $password viene ricevuta in chiaro e hashata QUI con password_hash().
     * Il chiamante (registrazione.php) non deve mai occuparsi dell'hashing:
     * centralizzare questa responsabilità nella classe Storage garantisce
     * che la password venga sempre hashata prima di essere salvata.
     *
     * password_hash($password, PASSWORD_DEFAULT)
     *   → genera un hash sicuro usando bcrypt.
     *     PASSWORD_DEFAULT usa l'algoritmo più sicuro disponibile nella
     *     versione PHP installata (oggi bcrypt, domani potrebbe cambiare).
     *     Aggiunge automaticamente un "salt" casuale: due hash della stessa
     *     password producono stringhe diverse, proteggendo da rainbow table.
     *
     * empty($utenti) ? 1 : max(array_column($utenti, 'numero_tessera')) + 1
     *   → calcola il prossimo ID:
     *     - Se non ci sono utenti, il primo tessera è 1
     *     - Altrimenti prende il valore massimo tra tutti i numeri_tessera
     *       esistenti (array_column estrae solo quella colonna dall'array
     *       bidimensionale) e aggiunge 1
     *     Questo approccio è sicuro anche se venissero cancellati utenti:
     *     i numeri tessera non vengono mai riutilizzati.
     *
     * @param  string   $nome
     * @param  string   $cognome
     * @param  string   $dataIscrizione  formato Y-m-d (es. "2026-03-26")
     * @param  string   $password        password in chiaro — hashata internamente
     * @return int|null                  numero tessera assegnato, oppure null
     */
    public static function aggiungiUtente(
        string $nome,
        string $cognome,
        string $dataIscrizione,
        string $password
    ): ?int {

        /* Passo 1: carica gli utenti esistenti */
        $utenti = self::leggiUtenti();

        /* Passo 2: calcola il prossimo numero tessera */
        $prossimoId = empty($utenti)
            ? 1
            : max(array_column($utenti, 'numero_tessera')) + 1;

        /* Passo 3: costruisce il record del nuovo utente */
        $nuovoUtente = [
            'numero_tessera'  => $prossimoId,
            'nome'            => $nome,
            'cognome'         => $cognome,
            'data_iscrizione' => $dataIscrizione,
            /* La password viene hashata qui: mai salvare la password in chiaro */
            'password_hash'   => password_hash($password, PASSWORD_DEFAULT),
        ];

        /* Passo 4: aggiunge il nuovo utente in fondo all'array */
        $utenti[] = $nuovoUtente;

        /* Passo 5 & 6: salva e restituisce il numero tessera o null */
        return self::scriviUtenti($utenti) ? $prossimoId : null;
    }

    /**
     * utentiOrdinati()
     * ─────────────────────────────────────────────────────────────────────
     * Restituisce tutti gli utenti ordinati alfabeticamente per cognome,
     * con ordinamento secondario per nome in caso di omonimia.
     *
     * usort($array, $callback)
     *   → ordina l'array in-place usando una funzione di comparazione custom.
     *     La funzione riceve due elementi ($a e $b) e deve restituire:
     *       < 0  se $a va prima di $b
     *       = 0  se sono equivalenti (stessa posizione)
     *       > 0  se $b va prima di $a
     *
     * strcasecmp($a, $b)
     *   → confronta due stringhe in modo case-insensitive (senza distinzione
     *     maiuscole/minuscole). "rossi" e "Rossi" sono considerati uguali.
     *     Restituisce un intero negativo, zero o positivo.
     *
     * $cmp !== 0 ? $cmp : strcasecmp($a['nome'], $b['nome'])
     *   → se i cognomi sono diversi ($cmp != 0) usa quel risultato,
     *     altrimenti (cognomi identici) confronta anche i nomi.
     *     Questo è l'ordinamento a due livelli: primario per cognome,
     *     secondario per nome.
     *
     * @return array  Array degli utenti ordinato per cognome e nome
     */
    public static function utentiOrdinati(): array
    {
        $utenti = self::leggiUtenti();

        usort($utenti, function (array $a, array $b): int {
            /* Confronta i cognomi (case-insensitive) */
            $cmp = strcasecmp($a['cognome'], $b['cognome']);
            /* Se i cognomi sono diversi usa quel risultato,
               altrimenti ordina anche per nome */
            return $cmp !== 0 ? $cmp : strcasecmp($a['nome'], $b['nome']);
        });

        return $utenti;
    }
}