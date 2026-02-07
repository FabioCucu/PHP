<?php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta Nome="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="POST" action="elabora.php">
    <label for="nome">Nome: </label>
    <input id="nome" type="text" name="nome"><br><br>

    <label for="cognome">Cognome: </label>
    <input id="cognome" type="text" name="cognome"><br><br>

    <label for="email">E-mail: </label>
    <input id="email" type="email" name="email"><br><br>

    <label for="password">Password: </label>
    <input id="password" type="password" name="password"><br><br>

    <label for="eta">Età: </label>
    <input id="eta" type="number" name="eta"><br><br>

    <label >Sesso: </label><br>
    <label for="sessoM">M</label>
    <input id="sessoM" type="radio" name="sesso" value="male"><br>
    <label for="sessoF">F</label>
    <input id="sessoF" type="radio" name="sesso" value="female"><br><br>
    
    <label for="corso">Corso: </label><br>
    <label for="corsoPHP">PHP</label>
    <input id="corsoPHP" type="checkbox" name="corso[]" value="php"><br>
    <label for="corsoJAVA">JAVA</label>
    <input id="corsoJAVA" type="checkbox" name="corso[]" value="html"><br>
    <label for="corsoCSS">CSS</label>
    <input id="corsoCSS" type="checkbox" name="corso[]" value="css"><br>
    <label for="corsoJS">JS</label>
    <input id="corsoJS" type="checkbox" name="corso[]" value="js"><br><br>

    <label >Città di Residenza: </label><br>
    <select name="citta">
        <option value="">--Seleziona--</option>
        <option value="Roma">Roma</option>
        <option value="Milano">Milano</option>
        <option value="Firenze">Firenze</option>
        <option value="Rovigo">Rovigo</option>
        <option value="Napoli">Napoli</option>
        <option value="Palermo">Palermo</option>
    </select>
    
    <button type="submit"> Invia </button>
</body>
</html>
