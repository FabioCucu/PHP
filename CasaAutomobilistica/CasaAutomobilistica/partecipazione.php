<?php
require_once "configuration/DataBaseConn.php";
$dbconfig = require "Configuration/DBConf.php";
$db = DataBaseConn::getDB($dbconfig);

$msg        = "";
$tipo       = "";

// Carica piloti
$piloti = [];
try {
    $stmt = $db->prepare("SELECT CF, Nome, Cognome FROM piloti ORDER BY Cognome, Nome");
    $stmt->execute();
    $piloti = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} catch (PDOException $e) {
    die("Errore caricamento piloti: " . $e->getMessage());
}

// Carica gare
$gare = [];
try {
    $stmt = $db->prepare("SELECT Nome, Luogo FROM gare ORDER BY Nome");
    $stmt->execute();
    $gare = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} catch (PDOException $e) {
    die("Errore caricamento gare: " . $e->getMessage());
}

// POST: nuova partecipazione
if (isset($_POST['form_partecipazione'])) {
    $cf_pilota   = trim($_POST['par_pilota']   ?? "");
    $gara        = trim($_POST['par_gara']      ?? "");
    $posizione   = trim($_POST['par_posizione'] ?? "");
    $tempo       = trim($_POST['par_tempo']     ?? "");

    if ($cf_pilota === "" || $gara === "" || $posizione === "") {
        $msg  = "Pilota, gara e posizione sono obbligatori.";
        $tipo = "error";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO partecipare (Gare, CF, Posizione, Tempo) VALUES (:gare, :cf, :posizione, :tempo)");
            $stmt->bindValue(":gare",      $gara,           PDO::PARAM_STR);
            $stmt->bindValue(":cf",         $cf_pilota,           PDO::PARAM_STR);
            $stmt->bindValue(":posizione", (int)$posizione, PDO::PARAM_INT);
            $stmt->bindValue(":tempo",     $tempo ?: null,  PDO::PARAM_STR);
            $stmt->bindValue(":tempo",     $tempo ?: null,  PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();

            // Trova nome pilota per il messaggio
            $nomePilota = "";
            foreach ($piloti as $p) {
                if ($p['CF'] === $cf_pilota) {
                    $nomePilota = $p['Cognome'] . " " . $p['Nome'];
                    break;
                }
            }

            $msg  = "Partecipazione di \"" . htmlspecialchars($nomePilota) . "\" alla gara \"" . htmlspecialchars($gara) . "\" registrata (P" . (int)$posizione . ").";
            $tipo = "success";
        } catch (PDOException $e) {
            $msg  = "Errore: " . $e->getMessage();
            $tipo = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partecipazione — Grand Prix</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<?php include "nav.php"; ?>
<main>
    <div class="page-hero">
        <h1>Partecipazione <span>Gara</span></h1>
        <p>Assegna posizione e tempo a un pilota per una gara</p>
    </div>

    <div class="cards-grid" style="grid-template-columns: 1fr;">

        <div class="card">
            <div class="card-header">
                <h2>Registra Risultato</h2>
            </div>

            <?php if ($msg !== ""): ?>
                <div class="msg <?= $tipo ?>"><?= $msg ?></div>
            <?php endif; ?>

            <form method="POST" action="partecipazione.php">
                <input type="hidden" name="form_partecipazione" value="1">

                <div class="form-row">
                    <!-- PILOTA -->
                    <div class="form-group">
                        <label for="par_pilota">Pilota</label>
                        <select id="par_pilota" name="par_pilota" required>
                            <option value="">-- Seleziona pilota --</option>
                            <?php foreach ($piloti as $p): ?>
                                <option value="<?= htmlspecialchars($p['CF']) ?>"
                                    <?= (isset($_POST['par_pilota']) && $_POST['par_pilota'] === $p['CF']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['Cognome'] . " " . $p['Nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- GARA -->
                    <div class="form-group">
                        <label for="par_gara">Gara</label>
                        <select id="par_gara" name="par_gara" required>
                            <option value="">-- Seleziona gara --</option>
                            <?php foreach ($gare as $g): ?>
                                <option value="<?= htmlspecialchars($g['Nome']) ?>"
                                    <?= (isset($_POST['par_gara']) && $_POST['par_gara'] === $g['Nome']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($g['Nome']) ?> — <?= htmlspecialchars($g['Luogo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- POSIZIONE -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="par_posizione">Posizione</label>
                        <input type="number" id="par_posizione" name="par_posizione"
                               min="1" max="20" placeholder="es. 1"
                               value="<?= htmlspecialchars($_POST['par_posizione'] ?? '') ?>"
                               required>
                    </div>
                </div>

                <!-- TEMPO -->
                <div class="form-group">
                    <label for="par_tempo">Tempo (opzionale)</label>
                    <input type="text" id="par_tempo" name="par_tempo"
                           placeholder="es. 1:32:45.123"
                           value="<?= htmlspecialchars($_POST['par_tempo'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary">REGISTRA RISULTATO</button>
            </form>
        </div>

    </div>

    <div class="cta-classifica">
        <a href="classifica.php" class="btn btn-classifica">VISUALIZZA TUTTI GLI ISCRITTI</a>
    </div>

</main>
</body>
</html>