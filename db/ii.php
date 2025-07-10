<?php

$servername = "localhost:8889";
$username = "root";
$password = "root";
$dbname = "delta_security";

try {

    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);

    echo "База данных '$dbname' успешно создана или уже существовала";
}
catch(PDOException $e) {
    echo "Ошибка при создании базы данных: " . $e->getMessage();
}

$conn = null;
