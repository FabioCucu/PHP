<?php

// ============================ Scrittura su file ============================

$classe = [
    "studente1"=>["nome1","cognome1",1],
    "studente2"=>["nome2","cognome2",2],
    "studente3"=>["nome3","cognome3",3],
    "studente4"=>["nome4","cognome4",4],
    "studente5"=>["nome5","cognome5",5],
    "studente6"=>["nome6","cognome6",6],
];

$file = fopen("voto.txt", "w");
foreach($classe as $keys => $stud)
{
    $line = $keys." = ".implode(",", $stud).PHP_EOL;//implode -> Array in stringa
    fwrite($file,$line);//File grandi, in alternativa, usare file_put_contents
}

// ============================ Lettura su file ============================

$datiFromFile = [];
$file = fopen("voto.txt", "r");
while(($line = fgets($file)) !== false) { // Similmente a fwrite <=> file_put_contents, abbiamo file_get_contents
    $datiFromFile = trim($line);
}
fclose($file);
foreach($datiFromFile as $dati) {
    echo $dati."<br>";
}
echo $datiFromFile[1]."<br>";

echo "<br><br>";

// ============================ Explode stringa in array ============================

$Stringa = "The moon is red tonight. You shall know death. - (By Remilia)";
$arrayFrase = explode(" ", $Stringa);
foreach($arrayfrase as $parola) {
    echo $parola."<br>";
}
