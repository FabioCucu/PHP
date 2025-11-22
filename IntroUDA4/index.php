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

echo "Il valore della variabile è: ".$var; // Il valore si concatena tramite il punto -> (.)

echo "<br>";

var_dump($var);

echo "<br>";

$var2 = 2.50; // Il tipo di variabile è automaticamente deciso da PHP.

echo "Il valore della variabile è: ".$var2;

echo "<br>";

var_dump($var2);

echo "<br>";

$var2 = "Ciao"; // Una variabile può essere sovrascritta anche se di tipi diversi.

var_dump($var2);

echo "<br>";
echo "<br>";

echo "============================ Valori ============================";

echo "<br>";

echo M_PI;

echo "<br>";

echo PHP_INT_MAX;

echo "<br>";

echo PHP_INT_MIN;