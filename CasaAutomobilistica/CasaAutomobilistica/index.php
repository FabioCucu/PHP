<?php
require_once "configuration/DataBaseConn.php";
$dbconfig = require "Configuration/DBConf.php";
$db = DataBaseConn::getDB($dbconfig);

$msg = "";
$msg_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome']      ?? "");
    $luogo     = trim($_POST['luogo']     ?? "");
    $lunghezza = trim($_POST['lunghezza'] ?? "");
    $npart     = trim($_POST['npart']     ?? "");

    if ($nome === "" || $luogo === "") {
        $msg = "Nome e luogo sono obbligatori.";
        $msg_type = "error";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO Gare (Nome, Luogo, Lunghezza, NPartecipanti) VALUES (:nome, :luogo, :lunghezza, :npart)");
            $stmt->bindValue(":nome",      $nome,                PDO::PARAM_STR);
            $stmt->bindValue(":luogo",     $luogo,               PDO::PARAM_STR);
            $stmt->bindValue(":lunghezza", $lunghezza ?: null,   PDO::PARAM_STR);
            $stmt->bindValue(":npart",     $npart     ?: null,   PDO::PARAM_INT);
            $stmt->execute();
            $stmtC = $db->prepare("INSERT INTO Classifiche (Nome) VALUES (:nome)");
            $stmtC->bindValue(":nome", $nome, PDO::PARAM_STR);
            $stmtC->execute();
            $stmtC->closeCursor();
            $stmt->closeCursor();
            $msg = "Gran Premio \"" . htmlspecialchars($nome) . "\" registrato.";
            $msg_type = "success";
            $nome = $luogo = $lunghezza = $npart = "";
        } catch (PDOException $e) {
            $msg = "Errore DB: " . $e->getMessage();
            $msg_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuova Gara — Grand Prix</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<?php include "nav.php"; ?>
<main>
    <div class="page-hero">
        <h1>Nuova <span>Gara</span></h1>
        <p>Aggiungi un Gran Premio al campionato</p>
    </div>
    <div class="card-single">
        <div class="card-header">
            <h2>Dati Gran Premio</h2>
        </div>
        <?php if ($msg !== ""): ?>
            <div class="msg <?= $msg_type ?>"><?= $msg ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="nome">Nome Gran Premio</label>
                <input type="text" id="nome" name="nome" placeholder="es. GP d'Italia" required
                       value="<?= htmlspecialchars($nome ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="luogo">Circuito / Luogo</label>
                <input type="text" id="luogo" name="luogo" placeholder="es. Monza" required
                       value="<?= htmlspecialchars($luogo ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="lunghezza">Lunghezza (km)</label>
                    <input type="number" step="0.001" id="lunghezza" name="lunghezza" placeholder="5.793"
                           value="<?= htmlspecialchars($lunghezza ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="npart">N. Partecipanti</label>
                    <input type="number" id="npart" name="npart" placeholder="20"
                           value="<?= htmlspecialchars($npart ?? '') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">AGGIUNGI GARA</button>
        </form>
    </div>
</main>
</body>
</html>