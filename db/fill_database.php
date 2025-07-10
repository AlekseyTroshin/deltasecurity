<?php
$servername = "mysql:host=localhost;dbname=delta_security;charset=utf8";
$username = "root";
$password = "root";
$dbname = "delta_security";


try {

    $conn = new PDO($servername, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);
    echo "База данных '$dbname' создана или уже существовала<br>";


    $conn->exec("USE $dbname");


    $sql = "CREATE TABLE IF NOT EXISTS books (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                img VARCHAR(255)
            )";
    $conn->exec($sql);
    echo "Таблица 'books' создана или уже существовала<br>";

    $sql = "CREATE TABLE IF NOT EXISTS genres (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL UNIQUE
            )";
    $conn->exec($sql);
    echo "Таблица 'genres' создана или уже существовала<br>";

    $sql = "CREATE TABLE IF NOT EXISTS authors (
                id INT PRIMARY KEY AUTO_INCREMENT,
                full_name VARCHAR(255) NOT NULL UNIQUE
            )";
    $conn->exec($sql);
    echo "Таблица 'authors' создана или уже существовала<br>";

    $sql = "CREATE TABLE IF NOT EXISTS book_genres (
                book_id INT NOT NULL,
                genre_id INT NOT NULL,
                PRIMARY KEY (book_id, genre_id),
                FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
                FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
            )";
    $conn->exec($sql);
    echo "Таблица 'book_genres' создана или уже существовала<br>";

    $sql = "CREATE TABLE IF NOT EXISTS book_authors (
                book_id INT NOT NULL,
                author_id INT NOT NULL,
                PRIMARY KEY (book_id, author_id),
                FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
                FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
            )";
    $conn->exec($sql);
    echo "Таблица 'book_authors' создана или уже существовала<br>";


    include 'fill_data.php';

    echo "Все операции успешно выполнены!";
}
catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

$conn = null;