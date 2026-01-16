<?php

/*
 * function somma($a, $b)
 * {
 *      return $a + $b;
 * }
*/// Poco leggibile, cosa accetta? cosa restituisce?
function somma2(int|float $a, int|float $b) : int|float
{
    return $a + $b;
}

//echo somma2("ciao", "addio"); // Non funziona...ovviamente.

echo "Risultato della somma è: ".somma2(4, 5);

echo "<br><br>";
function stampa(int|string $v) : void
{
    echo "Hai passato: ".$v;
}

stampa("The moon is red tonight. You shall know death.");

echo "<br><br>============================ Metodo con null-control ============================<br><br>";

function saluta(?string $nome) : string // ?string = la variabile può essere di tipo string o null
{
    return "Ciao ".($nome ?? "Ospite")."<br><br>"; // Ciao <nome> se una stringa è inserita, Ciao Ospite se la variabile $nome è null
}

echo saluta(null);
echo saluta("Fabio");

function saluta2(string $nome="Utente") : string
{
    return "Ciao ".$nome."<br><br>";
}

echo saluta2();
echo saluta2("Marco");

$myvar = 10;

function myfunc($myvar)
{
    $myvar = $myvar + 1;
    echo $myvar; // La funzione NON vedrà myvar dichiarato all'esterno, e nonostante lo modifichi il valore al di fuori della funzione rimarrà invariato.
}

$numeri = [1,2,3,4,5,6,7,8,9,0];
function sommaTotale(...$numeri) : int
{
    return array_sum($numeri);
}

/* Funzione di "Callback" è una funziona passata per argomento
 * ad un'altra funzione che può essere utilizzata in 2 momenti
 * o al verificarsi di un evento.
 */

echo "<br><br>============================ Metodo con callback ============================<br><br>";
function esegui($callback)
{
    $callback();
}

function saluta3()
{
    return "Ciao";
}

esegui("saluta3");

echo "<br><br>";
function applica($callback, $val)
{
    return $callback($val);
}

function doppio($x)
{
    return $x * 2;
}

echo applica("doppio", 5);

echo "<br><br>";

// Callback con funzione anonima

echo applica(function ($x){
    return $x * 2;
}, 5);

echo "<br><br>";

// Arrow Function - Funzioni anonime compatte
$doppio2 = fn($x) => $x * 2;
echo $doppio2(4);

// La Lambda expression vale se si ha UNA solo espressione

echo applica(fn($x) => $x * 2, 5);