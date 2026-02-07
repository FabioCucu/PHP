<?php

return [
    "dsn" => "mysql:host=192.168.60.144;dbname=fabio_cucu_itis;charset=utf8mb4",
    "username" => "fabio_cucu",
    "password" => "attiva.agonizzare.",
    "options" => [
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    ]
];