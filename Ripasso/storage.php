<?php
/**
 * storage.php
 * ──────────────────────────────────────────────────────────────────────────
 * Livello di persistenza basato su file JSON.
 * Sostituisce completamente il database MySQL.
 *
 * Il file utenti.json viene salvato nella cartella data/ (creata
 * automaticamente se non esiste) con i seguenti campi per utente:
 *   - numero_tessera  (int, auto-increment)
 *   - nome            (string)
 *   - cognome         (string)
 *   - data_iscrizione (string Y-m-d)
 *   - password_hash   (string bcrypt)
 * ──────────────────────────────────────────────────────────────────────────
 */
class Storage
{
    /** Percorso del file JSON dove vengono salvati gli utenti */
    private static string $file = __DIR__ . '/data/utenti.json';

    /**
     * Assicura che la cartella data/ esista e sia scrivibile.
     */
    private static function init(): void
    {
        $dir = dirname(self::$file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * Legge tutti gli utenti dal file JSON.
     * Restituisce un array (vuoto se il file non esiste ancora).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function leggiUtenti(): array
    {
        self::init();
        if (!file_exists(self::$file)) {
            return [];
        }
        $json = file_get_contents(self::$file);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Salva l'array completo degli utenti nel file JSON.
     *
     * @param  array $utenti
     * @return bool  true se il salvataggio è riuscito
     */
    private static function scriviUtenti(array $utenti): bool
    {
        self::init();
        $json = json_encode($utenti, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents(self::$file, $json, LOCK_EX) !== false;
    }

    /**
     * Aggiunge un nuovo utente e restituisce il numero di tessera assegnato.
     * Restituisce null in caso di errore di scrittura.
     *
     * @param  string $nome
     * @param  string $cognome
     * @param  string $dataIscrizione  formato Y-m-d
     * @param  string $password        password in chiaro (verrà hashata qui)
     * @return int|null                numero tessera assegnato oppure null
     */
    public static function aggiungiUtente(
        string $nome,
        string $cognome,
        string $dataIscrizione,
        string $password
    ): ?int {
        $utenti = self::leggiUtenti();

        /* Calcola il prossimo numero tessera (max esistente + 1, oppure 1) */
        $prossimoId = empty($utenti)
            ? 1
            : max(array_column($utenti, 'numero_tessera')) + 1;

        $nuovoUtente = [
            'numero_tessera'  => $prossimoId,
            'nome'            => $nome,
            'cognome'         => $cognome,
            'data_iscrizione' => $dataIscrizione,
            'password_hash'   => password_hash($password, PASSWORD_DEFAULT),
        ];

        $utenti[] = $nuovoUtente;

        return self::scriviUtenti($utenti) ? $prossimoId : null;
    }

    /**
     * Restituisce tutti gli utenti ordinati per cognome, poi nome.
     *
     * @return array
     */
    public static function utentiOrdinati(): array
    {
        $utenti = self::leggiUtenti();
        usort($utenti, function (array $a, array $b): int {
            $cmp = strcasecmp($a['cognome'], $b['cognome']);
            return $cmp !== 0 ? $cmp : strcasecmp($a['nome'], $b['nome']);
        });
        return $utenti;
    }
}