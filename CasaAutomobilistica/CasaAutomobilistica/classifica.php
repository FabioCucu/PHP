<?php
require_once "configuration/DataBaseConn.php";
$dbconfig = require "Configuration/DBConf.php";
$db = DataBaseConn::getDB($dbconfig);

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

// Carica classifica se gara selezionata
$risultati   = [];
$garaScelta  = trim($_GET['gara'] ?? "");

if ($garaScelta !== "") {
    try {
        $stmt = $db->prepare("SELECT pa.Posizione, pi.Cognome, pi.Nome, pi.Numero, pa.Tempo
            FROM partecipare pa
            JOIN piloti pi ON pi.CF = pa.CF
            WHERE pa.Gare = :gare
            ORDER BY pa.Posizione ASC");
        $stmt->bindValue(":gare", $garaScelta, PDO::PARAM_STR);
        $stmt->execute();
        $risultati = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
    } catch (PDOException $e) {
        die("Errore caricamento classifica: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica — Grand Prix</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<?php include "nav.php"; ?>
<main>
    <div class="page-hero">
        <h1>Classifica <span>Gara</span></h1>
        <p>Seleziona una gara per visualizzare la classifica</p>
    </div>

    <div class="cards-grid" style="grid-template-columns: 1fr;">
        <div class="card">
            <div class="card-header">
                <h2>Seleziona Gara</h2>
            </div>

            <form method="GET" action="classifica.php">
                <div class="form-group">
                    <label for="gara">Gara</label>
                    <select id="gara" name="gara" required onchange="this.form.submit()">
                        <option value="">-- Seleziona gara --</option>
                        <?php foreach ($gare as $g): ?>
                            <option value="<?= htmlspecialchars($g['Nome']) ?>"
                                    <?= $garaScelta === $g['Nome'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['Nome']) ?> — <?= htmlspecialchars($g['Luogo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($garaScelta !== ""): ?>
            <div class="card">
                <div class="card-header">
                    <h2>Classifica: <?= htmlspecialchars($garaScelta) ?></h2>
                </div>

                <?php if (empty($risultati)): ?>
                    <p>Nessun risultato disponibile per questa gara.</p>
                <?php else: ?>
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th style="text-align:left; padding:8px 12px;">Pos</th>
                            <th style="text-align:left; padding:8px 12px;">Pilota</th>
                            <th style="text-align:left; padding:8px 12px;">Numero</th>
                            <th style="text-align:left; padding:8px 12px;">Tempo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($risultati as $r): ?>
                            <tr>
                                <td style="padding:8px 12px;"><?= (int)$r['Posizione'] ?></td>
                                <td style="padding:8px 12px;"><?= htmlspecialchars($r['Cognome'] . " " . $r['Nome']) ?></td>
                                <td style="padding:8px 12px;"><?= htmlspecialchars($r['Numero']) ?></td>
                                <td style="padding:8px 12px;"><?= $r['Tempo'] ? htmlspecialchars($r['Tempo']) : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</main>
</body>
</html>