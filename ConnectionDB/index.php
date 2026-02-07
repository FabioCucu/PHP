<?php

$db = new PDO(
    "mysql:host=192.168.60.144;dbname=fabio_cucu_itis;charset=utf8mb4",
    "fabio_cucu",
    "attiva.agonizzare.",
    [
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    ]
);

// READ 1

echo "<h1>READ GENERALE</h1>";

$query = "SELECT * FROM studenti";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();

    while ($user = $stmt->fetch())
    {
        echo "cognome: ".$user->cognome."<br>";
        echo "nome: ".$user->nome."<br>";
        echo "media: ".$user->media."<br>";
        echo "data_iscrizione: ".$user->data_iscrizione."<br>";
        echo "<hr>";
    }
    $stmt->closeCursor();
}
catch (PDOException $e)
{
    echo "A DB error occured. Please try again later.";
}

// READ 2

echo "<h1>READ FILTRATO</h1>";

$name = "Francesco";

$query = "SELECT media, cognome FROM studenti WHERE nome = :name"; // :name è un marker, come una variabile,

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(":name", $name, PDO::PARAM_STR); // bindValue assegna a :name
    $stmt->execute();

    while ($user = $stmt->fetch())
    {
        echo "cognome: ".$user->cognome."<br>";
        echo "media: ".$user->media."<br>";
        echo "<hr>";
    }
    $stmt->closeCursor();
}
catch (PDOException $e)
{
    echo "A DB error occured. Please try again later.";
}

// CREATE

echo "<h1>AGGIUNTA DATI E READ</h1>";

$query = "INSERT INTO studenti (nome, cognome, media, data_iscrizione) VALUES (:nome, :cognome, :media, NOW())";

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(":nome", "Lucy", PDO::PARAM_STR);
    $stmt->bindValue(":cognome", "Taylor", PDO::PARAM_STR);
    $stmt->bindValue(":media", 8, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();
}
catch (PDOException $e)
{
    echo "A DB error occured. Please try again later.";
}

$query = "SELECT * FROM studenti";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();

    while ($user = $stmt->fetch())
    {
        echo "cognome: ".$user->cognome."<br>";
        echo "nome: ".$user->nome."<br>";
        echo "media: ".$user->media."<br>";
        echo "data_iscrizione: ".$user->data_iscrizione."<br>";
        echo "<hr>";
    }
    $stmt->closeCursor();
}
catch (PDOException $e)
{
    echo "A DB error occured. Please try again later.";
}

// UPDATE

$query = "UPDATE studenti SET media = :media WHERE nome = :nome";

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(":nome", "Luca", PDO::PARAM_STR);
    $stmt->bindValue(":media", 8, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt -> rowCount() === 0)
    {
        echo "<h2>No rows were updated.</h2> <br>";
    }
    else
    {
        echo "<h2>Update Successful</h2> <br>";
    }
}
catch (PDOException $e)
{
    echo "A DB error occured. Please try again later.";
}

// DELETE

$query = "DELETE FROM studenti WHERE nome = :nome";

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(":nome", "Lucy", PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt -> rowCount() === 0)
    {
        echo "<h2>No rows were updated.</h2> <br>";
    }
    else
    {
        echo "<h2>Delete Successful</h2> <br>";
    }
}
catch (PDOException $e)
{
    echo "A DB error occured. Please try again later.";
}

echo "<h1>READ GENERALE POST DELETE</h1>";

$query = "SELECT * FROM studenti";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();

    while ($user = $stmt->fetch())
    {
        echo "cognome: ".$user->cognome."<br>";
        echo "nome: ".$user->nome."<br>";
        echo "media: ".$user->media."<br>";
        echo "data_iscrizione: ".$user->data_iscrizione."<br>";
        echo "<hr>";
    }
    $stmt->closeCursor();
}
catch (PDOException $e)
{
    echo "A DB error occured. Please try again later.";
}
