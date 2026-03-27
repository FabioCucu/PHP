<?php
require_once "configuration/DataBaseConn.php";
$dbconfig = require "Configuration/DBConf.php";
$db = DataBaseConn::getDB($dbconfig);

$msg_casa    = "";
$msg_pilota  = "";
$tipo_casa   = "";
$tipo_pilota = "";

// Carica case per la select
$caseAuto = [];

try {
    $stmt = $db->prepare("SELECT Nome FROM case_automobilistiche ORDER BY Nome");
    $stmt->execute();
    $caseAuto = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} catch (PDOException $e) {
    die("Errore caricamento scuderie: " . $e->getMessage());
}

// POST: nuova scuderia
if (isset($_POST['form_casa'])) {
    $nome   = trim($_POST['casa_nome']   ?? "");
    $colore = trim($_POST['casa_colore'] ?? "#000000");
    if ($nome === "") {
        $msg_casa  = "Il nome è obbligatorio.";
        $tipo_casa = "error";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO case_automobilistiche (Nome, ColoreLivrea) VALUES (:nome, :colore)");
            $stmt->bindValue(":nome",   $nome,   PDO::PARAM_STR);
            $stmt->bindValue(":colore", $colore, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            $msg_casa  = "Scuderia \"" . htmlspecialchars($nome) . "\" registrata.";
            $tipo_casa = "success";
            // Ricarica select
            $stmt2 = $db->prepare("SELECT Nome FROM case_automobilistiche ORDER BY Nome");
            $stmt2->execute();
            $caseAuto = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $stmt2->closeCursor();
        } catch (PDOException $e) {
            $msg_casa  = "Errore (nome già esistente?).";
            $tipo_casa = "error";
        }
    }
}

// POST: nuovo pilota
if (isset($_POST['form_pilota'])) {
    $cf     = trim($_POST['pil_cf']      ?? "");
    $nome   = trim($_POST['pil_nome']    ?? "");
    $cogn   = trim($_POST['pil_cognome'] ?? "");
    $naz    = trim($_POST['pil_naz']     ?? "");
    $numero = trim($_POST['pil_numero']  ?? "");
    $casa   = trim($_POST['pil_casa']    ?? "");

    if ($cf === "" || $nome === "" || $cogn === "" || $numero === "" || $casa === "") {
        $msg_pilota  = "CF, nome, cognome, numero e scuderia sono obbligatori.";
        $tipo_pilota = "error";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO Piloti (CF, Nome, Cognome, Nazionalita, Numero, CasaAutomobilistica) VALUES (:cf, :nome, :cogn, :naz, :numero, :casa)");
            $stmt->bindValue(":cf",     $cf,              PDO::PARAM_STR);
            $stmt->bindValue(":nome",   $nome,            PDO::PARAM_STR);
            $stmt->bindValue(":cogn",   $cogn,            PDO::PARAM_STR);
            $stmt->bindValue(":naz",    $naz ?: null,     PDO::PARAM_STR);
            $stmt->bindValue(":numero", (int)$numero,     PDO::PARAM_INT);
            $stmt->bindValue(":casa",   $casa,            PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            $msg_pilota  = "Pilota \"" . htmlspecialchars($cogn . " " . $nome) . "\" registrato.";
            $tipo_pilota = "success";
        } catch (PDOException $e) {
            $msg_pilota  = "Errore: " . $e->getMessage();
            $tipo_pilota = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iscrizione — Grand Prix</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<?php include "nav.php"; ?>
<main>
    <div class="page-hero">
        <h1>Iscrizione <span>Campionato</span></h1>
        <p>Registra scuderie e piloti per la stagione</p>
    </div>

    <div class="cards-grid">

        <!-- SCUDERIA -->
        <div class="card">
            <div class="card-header">
                <h2>Nuova Scuderia</h2>
            </div>
            <?php if ($msg_casa !== ""): ?>
                <div class="msg <?= $tipo_casa ?>"><?= $msg_casa ?></div>
            <?php endif; ?>
            <form method="POST" action="iscrizione.php">
                <input type="hidden" name="form_casa" value="1">
                <div class="form-group">
                    <label for="casa_nome">Nome Scuderia</label>
                    <input type="text" id="casa_nome" name="casa_nome" placeholder="es. Ferrari" required>
                </div>
                <div class="form-group">
                    <label for="casa_colore">Colore Livrea</label>
                    <div class="color-row">
                        <input type="color" id="casa_colore" name="casa_colore" value="#e8002d">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">REGISTRA SCUDERIA</button>
            </form>
        </div>

        <!-- PILOTA -->
        <div class="card">
            <div class="card-header">
                <h2>Nuovo Pilota</h2>
            </div>
            <?php if ($msg_pilota !== ""): ?>
                <div class="msg <?= $tipo_pilota ?>"><?= $msg_pilota ?></div>
            <?php endif; ?>
            <form method="POST" action="iscrizione.php">
                <input type="hidden" name="form_pilota" value="1">
                <div class="form-group">
                    <label for="pil_cf">Codice Fiscale</label>
                    <input type="text" id="pil_cf" name="pil_cf" placeholder="LCRCRL97M16Z114I" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="pil_nome">Nome</label>
                        <input type="text" id="pil_nome" name="pil_nome" placeholder="Charles" required>
                    </div>
                    <div class="form-group">
                        <label for="pil_cognome">Cognome</label>
                        <input type="text" id="pil_cognome" name="pil_cognome" placeholder="Leclerc" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="pil_naz">Nazionalità</label>
                        <input type="text" id="pil_naz" name="pil_naz" placeholder="Monegasco">
                    </div>
                    <div class="form-group">
                        <label for="pil_numero">Numero</label>
                        <input type="number" id="pil_numero" name="pil_numero" placeholder="16" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pil_casa">Scuderia</label>
                    <select id="pil_casa" name="pil_casa" required>
                        <option value="">-- Seleziona scuderia --</option>
                        <?php foreach ($caseAuto as $g): ?>
                            <option value="<?= htmlspecialchars($g['Nome']) ?>">
                                <?= htmlspecialchars($g['Nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-accent">REGISTRA PILOTA</button>
            </form>
        </div>
    </div>

    <div class="cta-classifica">
        <a href="classifica.php" class="btn btn-classifica">VISUALIZZA TUTTI GLI ISCRITTI</a>
    </div>

</main>
</body>
</html>