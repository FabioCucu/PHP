-- ============================================================
-- schema.sql
-- Schema del database per la Biblioteca Comunale
-- ============================================================
-- Eseguire questo file una sola volta per creare il database
-- e la tabella degli utenti.
-- Comando: mysql -u root -p < schema.sql
-- ============================================================

-- Crea il database se non esiste già, usando utf8mb4 per
-- supportare tutti i caratteri Unicode (incluse emoji).
CREATE DATABASE IF NOT EXISTS biblioteca_comunale
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Seleziona il database appena creato
USE biblioteca_comunale;

-- ============================================================
-- Tabella: utenti
-- Contiene i dati degli utenti iscritti alla biblioteca.
-- ============================================================
CREATE TABLE IF NOT EXISTS utenti (
    -- Chiave primaria auto-incrementante: il numero di tessera
    -- viene assegnato automaticamente dal database in modo progressivo.
    numero_tessera   INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,

    -- Nome dell'utente: obbligatorio, max 50 caratteri
    nome             VARCHAR(50)     NOT NULL,

    -- Cognome dell'utente: obbligatorio, max 50 caratteri
    cognome          VARCHAR(50)     NOT NULL,

    -- Data di iscrizione: valorizzata automaticamente con la data odierna
    -- se non viene specificata durante l'INSERT.
    data_iscrizione  DATE            NOT NULL DEFAULT (CURRENT_DATE),

    -- Password dell'utente: salvata come hash SHA-256 (64 caratteri hex).
    -- NON salvare mai la password in chiaro!
    password_hash    VARCHAR(255)    NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
