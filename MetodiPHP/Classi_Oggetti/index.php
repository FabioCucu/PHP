<?php
// Classe e oggetto

class Studente {
    private string $nome;
    private int $eta;
    private static $numero;

    public function setNome(string $s)
    {
        $this->nome = $s;
    }

    public function setEta($v)
    {
        $this->eta = $v;
    }
    public function saluta() : void
    {
        echo "Ciao, sono $this->nome. Ho $this->eta anni.";
    }
    public static function presentazione() : void
    {
        self::$numero++;
        echo "Ciao sono studente numero ";
        echo self::$numero;
        echo "<br><br>";
    }
}

$s = new Studente();

$s->setNome("Marco");
$s->setEta(18);
$s->saluta();

echo "<br><br>";

$s2 = new Studente();

$s2->setNome("Gianni");
$s2->setEta(19);
$s2->saluta();

echo "<br><br>";

Studente::presentazione();
Studente::presentazione();
Studente::presentazione();
Studente::presentazione();