<?php
// Server-Side (PHP)
$Stringa = "The moon is red tonight. You shall know death. - (Remilia)";

$SDM = ["Remilia Scarlet", "Flandre Scarlet", "Sakuya Izayoi", "Patchouli Knowledge", "Hong Meiling"];
$Scarlet = "A vampire who is the head of the Scarlet Devil Mansion.";
$Izayoi = "Only human and chief maid of the Scarlet Devil Mansion.";
$Knowledge = "A Youkai Magician/witch and resident of the Scarlet Devil Mansion in the library.";
$Meiling = "A Youkai who serves as the gatekeeper of the Scarlet Devil Mansion.";

$msg = "Touhou best game.";

$Data = [
    [
        "Name" => "Remilia",
        "Surname" => "Scarlet",
        "Age" => "500+"
    ],
    [
        "Name" => "Flandre",
        "Surname" => "Scarlet",
        "Age" => "495+"
    ],
    [
        "Name" => "Sakuya",
        "Surname" => "Izayoi",
        "Age" => "Unknown"
    ],
    [
        "Name" => "Patchouli",
        "Surname" => "Knowledge",
        "Age" => "Around 100"
    ],
    [
        "Name" => "Hong",
        "Surname" => "Meiling",
        "Age" => "Less than 100"
    ]
];

$keys = array_keys($Data[0]);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<p>Ciao</p>
<p>==================================== Echo ====================================</p>
<p><?php echo $Stringa ?></p>
<p>================================ Echo Compatto =================================</p>
<p><?= $Stringa ?></p>
<!-- La forma compatta può essere usata quando bisogna visualizzare UNA variabile singola. -->
<!-- Il SERVER esegue le parti php ed al html viene "iniettato" solo il risultato. -->
<p>========================== Scarlet Devil Mansion - Members ==========================</p>
<?php
foreach ($SDM as $item) :
    ?>
    <!-- Il foreach, come altre tali istruzioni, non vanno inserite in un tag html siccome non danno alcun risultato.
    Se inserite, risulteranno nell'apparizione di un tag vuoto. -->
    <p><?= $item ?></p>
    <?php if ($item == "Remilia Scarlet" || $item == "Flandre Scarlet") : ?>
    <p><?= $Scarlet ?></p>
<?php elseif ($item == "Sakuya Izayoi"): ?>
    <p><?= $Izayoi ?></p>
<?php elseif ($item == "Patchouli Knowledge"): ?>
    <p><?= $Knowledge ?></p>
<?php elseif ($item == "Hong Meiling"): ?>
    <p><?= $Meiling ?></p>
<?php endif; ?>
    <hr>
<?php endforeach; ?>

<table>
    <?php
    foreach ($keys as $key):
        ?>
        <th><?= $key ?></th>
    <?php endforeach;
    foreach ($Data as $item) :
        ?>
        <!--<tr>
            <td><?= $item["Name"] ?></td>
            <td><?= $item["Surname"] ?></td>
            <td><?= $item["Age"] ?></td>
        </tr>-->
        <tr>
            <?php foreach ($item as $value): ?>
                <td><?= $value ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>

<!--<?php
$i = 1;
while ($i <= 50):?>
    <?php
    echo $i;
    $i = $i + 1;
endwhile;
?>-->
<button id="myButton">ToDo</button>
<script> const message = <?=json_encode($msg)?> </script>
<!-- json_encode trasforma il contenuto che stiamo inviando in un formato json, rendendo possibile al javascript di ottenere dei dati dal php -->
<script src="script.js"></script>
</body>
<footer>
    <p>&copy; 2026 Il Tuo Sito. Tutti i diritti riservati. | <a href="#" style="color: white; text-decoration: underline;">Privacy Policy</a> | <a href="#" style="color: white; text-decoration: underline;">Termini di Servizio</a></p>
</footer>
</html>
