<?php
echo $Stringa = "The moon is red tonight.You shall know death. - (By Remilia)";

echo "<br><br>================================ Ricerca Pattern \"red\" ================================<br><br>";

// Ricerca il pattern all'interno della stringa
echo preg_match("#red#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>======================= Ricerca Pattern \"red\" direttamente con stringa ========================<br><br>";

// E' possibile inserire la stringa direttamente, invece che tramite una variabile
echo preg_match("#red#", "The moon is red tonight.You shall know death. - (By Remilia)") ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>============================= Ricerca Pattern \"red\" all'inizio =============================<br><br>";

// Il carattere "^" è utilizzato per limitare la ricerca della parola all'inizio della stringa
echo preg_match("#^red#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>============================= Ricerca Pattern \"red\" alla fine =============================<br><br>";

// Il carattere "$" è utilizzato per limitare la ricerca della parola all'inizio della stringa
echo preg_match("#red$#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>=============================== Ricerca Pattern numerico ===============================<br><br>";

// Le parentesi quadre [...-...] ricerca un range. Dentro la stringa ci deve essere almeno UNO dei caratteri specificati
echo preg_match("#[0-9]#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>========================== Ricerca Pattern alfabetico [A-C, a-c] ============================<br><br>";

// Le parentesi quadre [...-...] ricerca un range. Dentro la stringa ci deve essere almeno UNO dei caratteri specificati
echo preg_match("#[A-Ca-c]#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>========================= Ricerca Pattern numerico negato [^0-9] ===========================<br><br>";

// Il carattere "^" utilizzato all'INTERNO DELLE PARENTESI QUADRE [^...-...] sta a NEGARE ciò che è interno
echo preg_match("#[^0-9]#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>========================= Ricerca Pattern vocali sh[aeiou]ll ===========================<br><br>";

// Viene ricercata come pattern parole che inizato con le lettere presenti prima della parentesi quadra "sh-", uno (o più) caratteri inseriti nella parentesi, le lettere presenti dopo la parentesi quadra "-ll"
echo preg_match("#sh[aeiou]ll#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>========================= Ricerca Pattern vocali m[aeiou]?n ===========================<br><br>";

// Il carattere "?" limita a nessuno, o uno, carattere tra i selezionati.
echo preg_match("#m[aeiou]?n#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>========================= Ricerca Pattern vocali m[aeiou]*n ===========================<br><br>";

// Il carattere "*" limita a nessuno, uno, o infiniti, caratteri tra i selezionati.
echo preg_match("#m[aeiou]*n#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>========================= Ricerca Pattern vocali R[aeiou]*milia[0-9] ===========================<br><br>";

echo preg_match("#R[aeiou]*milia[0-9]#", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);

echo "<br><br>========================= Ricerca Pattern case-insensitive ===========================<br><br>";

// Il carattere "i" al termine del pattern rende la ricerca "case insensitive"
echo preg_match("#remilia#i", $Stringa, $matches) ? "true" : "false";

echo "<br><br>";
var_dump($matches);