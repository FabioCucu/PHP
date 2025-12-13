<?php

echo "Hello, World!"; // Output
echo "<br>"; // Necessario per andare a capo tra output.
echo "Buongiorno."; // echo "..." scrive gli output uno dopo l'altro.
echo "<br>";
echo "<br>";

echo "============================ Variabili ============================";

echo "<br>";
$var = 10; // Dichiaro una variabile e gli assegno un valore.
echo "<br>";
echo "Il valore della variabile è: " . $var; // Il valore si concatena tramite il punto -> (.)
echo "<br>";
var_dump($var);
echo "<br>";
$var2 = 2.50; // Il tipo di variabile è automaticamente deciso da PHP.
echo "Il valore della variabile è: " . $var2;
echo "<br>";
var_dump($var2);
echo "<br>";
$var2 = "Ciao"; // Una variabile può essere sovrascritta anche se di tipi diversi.
var_dump($var2);
echo "<br>";
echo "<br>";

echo "============================ Valori Matematici e Limiti ============================";

echo "<br>";
echo "<br>";
echo M_PI;
echo "<br>";
echo PHP_INT_MAX;
echo "<br>";
echo PHP_INT_MIN;
echo "<br>";
if ($var2 > 5)
    echo "Controllo Effettuato su var2";
else echo "Ciao, 2...";
echo "<br>";
echo "<br>";

echo "============================ Vettori ============================";

echo "<br>";
$vett = [];
$vett1 = [10, 20, 30];
echo "<br>";
echo "Visualizzo primo elemento del vettore";
echo $vett1[0];
echo "<br>";
echo "Conteggio elementi";
echo "<br>";
$n = count($vett1);
echo "$n";
echo "<br>";
echo "Stampa Elementi";
echo "<br>";
for ($i = 0; $i < $n; $i++)
{
    echo "$vett1[$i]";
    echo "<br>";
}
echo "Var_dump dell'Array";
echo "<br>";
var_dump($vett1);
echo "<br>";
$vett2 = [10, 20, 30, "a", "b"];
var_dump($vett2);
echo "<br>";
array_push($vett2, 40, 50);
var_dump($vett2);
echo "<br>";
echo "<br>";

echo "============================ Struct ============================";

echo "<br>";
echo "<br>";
$studente = [
    "nome" => "Marco",
    "cognome" => "Rossi",
    "eta" => 18
];
echo $studente["nome"];
echo "<br>";
echo $studente["cognome"];
echo "<br>";
foreach ($studente as $chiave => $value) {
    echo "$chiave: $value";
    echo "<br>";
}
echo "<br>";

echo "============================ Vettore Associativo Annidato ============================";

echo "<br>";
$studenti = [
    "studente1" => [
        "nome" => "Gigi",
        "voto" => 7
    ],

    "studente2" => [
        "nome" => "Gigi...2",
        "voto" => 7.2
    ]
];
echo $studenti["studente2"]["nome"];
echo "<br>";
echo $studenti["studente2"]["voto"];

$config = [
    "database" => "mio_db",
    "utente" => "admin",
    "password" => "12345"
];

echo "<br>";
if (array_key_exists("nome", $studente))
    echo ("Chiave trovata");
else
    echo ("I don't know.");
echo "<br>";
$chiavi = array_keys($studente);
var_dump($chiavi);
echo "<br>";
$valori = array_values($studente);
var_dump($valori);
echo "<br>";
echo "$valori[1]";
echo "<br>";
echo $studente["eta"];
echo "<br>";
echo next($studente);
echo "<br>";
echo "<br>";

echo "============================ '==', '===' e vari controlli sulle Variabili ============================";

echo "<br>";
$var1 = 5;
$var2 = "5";
echo "<br>";
if ($var1 == $var2)
{
    echo "Sono uguali.";
    echo "<br>";
    echo var_dump($var1);
    echo " == ";
    echo var_dump($var2);
}
echo "<br>";
if ($var1 === $var2)
{
    echo "Sono uguali.";
    echo "<br>";
    echo var_dump($var1);
    echo " == ";
    echo var_dump($var2);
}
else
{
    echo "Sono diversi.";
    echo "<br>";
    echo var_dump($var1);
    echo " != ";
    echo var_dump($var2);
}
echo "<br>";
$var3 = "Ciao";
$var4 = 0;
if ($var4 == $var3)
    echo "Esiste";
else
    echo "Non Esiste";
echo "<br>";
isset($a);// Restituisce false se la variabile  è Null o non esiste
echo "Restituisce false se la variabile  è Null o non esiste";
echo "<br>";
if (isset($a))
    echo "Esiste";
else
    echo "Non Esiste";
$var5 = null;
is_null(null); //Sempre vero....ovviamente
echo "<br>";
echo "====== is_null ====== <br>";
if (is_null($var5))
    echo "Var 5 è null";
else
    echo "Var 5 NON è null";
echo "<br>";
echo "====== isset ====== <br>";
if (isset($var5))
    echo "Var 5 esiste";
else
    echo "Var 5 NON esiste";
echo "<br>";
echo "====== empty ====== <br>";
if (empty($var5))
    echo "Var 5 è vuota";
else
    echo "Var 5 NON è vuota";
echo "<br>";
