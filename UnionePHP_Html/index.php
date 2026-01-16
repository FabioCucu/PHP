<?php
// Server-Side (PHP)
$Stringa = "The moon is red tonight. You shall know death. - (By Remilia)";

$SDM = ["Remilia Scarlet", "Flandre Scarlet", "Sakuya Izayoi", "Patchouli Knowledge", "Hong Meiling"];
$Scarlet = "A vampire who is the head of the Scarlet Devil Mansion.";
$Izayoi = "Only human and chief maid of the Scarlet Devil Mansion.";
$Knowledge = "A Youkai Magician/witch and resident of the Scarlet Devil Mansion in the library.";
$Meiling = "A Youkai who serves as the gatekeeper of the Scarlet Devil Mansion.";

$msg = "Touhou best game.";

$Data = [

        "Data1" => [
        "Name" => "Remilia",
        "Surname" => "Scarlet",
        "Age" => "500+"
    ],
        "Data2" => [
        "Name" => "Flandre",
        "Surname" => "Scarlet",
        "Age" => "495+"
    ],
        "Data3" => [
        "Name" => "Sakuya",
        "Surname" => "Izayoi",
        "Age" => "Unknown"
    ]];

$keys = array_keys($Data);

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
    <p>================================ Echo compatto =================================</p>
    <p><?=$Stringa?></p>

    <p>================================ Scarlet Devil Mansion members =================================</p>
    <?php
        foreach ($SDM as $item) :
    ?>
    <p><?=$item?></p>
            <?php
            if ($item == "Remilia Scarlet" || $item == "Flandre Scarlet") :
                ?>
                <p><?=$Scarlet?></p>
            <?php elseif ($item == "Sakuya Izayoi"):?>
                <p><?=$Izayoi?></p>
            <?php elseif ($item == "Patchouli Knowledge"):?>
                <p><?=$Knowledge?></p>
            <?php elseif ($item == "Hong Meiling"):?>
                <p><?=$Meiling?></p>
            <?php endif;?>
            <hr>
        <?php
        endforeach;
    ?>

    <table>
        <tr>
            <td><?=$keys[0] ?></td>
            <td><?=$keys[1] ?></td>
            <td><?=$keys[2] ?></td>
        </tr>
        <tr>
            <td><?=$Data[0][0] ?></td>
            <td><?=$Data[0][1] ?></td>
            <td><?=$Data[0][2] ?></td>
        </tr>
        <tr>
            <td><?=$Data[1][0] ?></td>
            <td><?=$Data[1][1] ?></td>
            <td><?=$Data[1][2] ?></td>
        </tr>
        <tr>
            <td><?=$Data[2][0] ?></td>
            <td><?=$Data[2][1] ?></td>
            <td><?=$Data[2][2] ?></td>
        </tr>
    </table>

    <button id="myButton">ToDo</button>
    <script> const message=<?=json_encode($msg)?> </script>
    <script src="script.js"></script>
</body>
</html>
