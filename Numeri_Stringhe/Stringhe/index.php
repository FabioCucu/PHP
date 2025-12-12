<?php

/*
 * strlen()
 * strrev()
 * strtolower()
 * strtoupper()
 * ucfirst()
 * ucwords()
 * trim()
 * ltrim()
 * rtrim()
 * explode()
 * implode()
 * str_replace()
 * substr()
 * strpos()
 * strrpos()
 * strstr()
 * stristr()
 * sprintf()
 * printf()
 * number_format()
 * addslashes()
 * stripslashes()
 * */

$Stringa = "The moon is red tonight. You shall know death. - (By Remilia)";

echo "============================ STRLEN() ============================<br><br>";

// strlen()
echo "Lunghezza della stringa:<br>";
echo $Stringa;
echo "<br> è: ";
echo $LunghezzaStringa = strlen($Stringa);

// strrev()

echo "<br><br>============================ STRREV() ============================<br><br>";

$StringaInvertita = strrev($Stringa);
echo "La stringa:<br>";
echo $Stringa;
echo "<br> invertita è: <br>";
echo $StringaInvertita;