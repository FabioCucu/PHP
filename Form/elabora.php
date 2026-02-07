<?php
//$_POST è un "super global", una variabile globale
//$dati = [
//    "Nome" => $_POST["nome"] ?? "",
//    "Cognome" => $_POST["cognome"] ?? "",
//    "Email" => $_POST["email"] ?? "",
//    "Password" => $_POST["password"] ?? "",
//    "Età" => $_POST["eta"] ?? "",
//    "Sesso" => $_POST["sesso"] ?? "",
//    "Corsi" => $_POST["corso"] ?? [],
//    "Città" => $_POST["citta"] ?? ""
//]
$nome = $_POST ["nome"] ?? "";
$cognome = $_POST ["cognome"] ?? "";
$email = $_POST ["email"] ?? "";
$password = $_POST ["password"] ?? "";
$eta = $_POST ["eta"] ?? "";
$sesso = $_POST ["sesso"] ?? "";
$corsi = $_POST ["corsi"] ?? [];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<p>Nome: <?=$nome?></p>
<p>Cognome: <?=$cognome?></p>
<p>E-Mail: <?=$email?></p>
<p>Password: <?=$password?></p>
<p>Età: <?=$eta?></p>
<p>Sesso: <?=$sesso?></p>
<!--<?php foreach ($dati as $campo => $valore): ?>
        <strong><?= $campo ?></strong>
        <?php
    if (is_array($valore)) {
        echo !empty($valore) ? implode(", ", $valore) : "Nessuno";
    } else {
        echo htmlspecialchars($valore);
    }
    ?>
        <br>
    <?php endforeach; ?>-->

<p>Lingua:
    <?php foreach ($lingua as $items) {
        echo $items . " ";
    }
    ?>
</p>
</body>
</html>