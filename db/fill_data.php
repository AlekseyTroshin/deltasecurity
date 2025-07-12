<?php
// Заполнение таблиц тестовыми данными
try {

    $conn->exec("DELETE FROM book_authors");
    $conn->exec("DELETE FROM book_genres");
    $conn->exec("DELETE FROM books");
    $conn->exec("DELETE FROM authors");
    $conn->exec("DELETE FROM genres");
    $conn->exec("ALTER TABLE books AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE authors AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE genres AUTO_INCREMENT = 1");


    $genres = ['Фантастика', 'Детектив', 'Роман', 'Фэнтези', 'Научная литература',
        'Исторический роман', 'Ужасы', 'Приключения', 'Биография', 'Поэзия'];

    $stmt = $conn->prepare("INSERT INTO genres (name) VALUES (:name)");
    foreach ($genres as $genre) {
        $stmt->execute([':name' => $genre]);
    }
    echo "Добавлены жанры<br>";


    $authors = [
        'Лев Толстой', 'Фёдор Достоевский', 'Джоан Роулинг',
        'Стивен Кинг', 'Агата Кристи', 'Джордж Оруэлл',
        'Рэй Брэдбери', 'Айзек Азимов', 'Эрнест Хемингуэй', 'Антон Чехов'
    ];

    $stmt = $conn->prepare("INSERT INTO authors (full_name) VALUES (:name)");
    foreach ($authors as $author) {
        $stmt->execute([':name' => $author]);
    }
    echo "Добавлены авторы<br>";


    $books = [
        ['Война и мир', 'Эпический роман о войне 1812 года', 'war_and_peace.jpg'],
        ['Преступление и наказание', 'Философский роман о преступлении и его последствиях', 'crime_and_punishment.jpg'],
        ['Гарри Поттер и философский камень', 'Первая книга о юном волшебнике', 'harry_potter_1.jpg'],
        ['Оно', 'Роман ужасов о древнем зле', 'it.jpg'],
        ['Убийство в Восточном экспрессе', 'Знаменитый детектив Эркюля Пуаро', 'orient_express.jpg'],
        ['1984', 'Антиутопия о тоталитарном обществе', '1984.jpg'],
        ['451 градус по Фаренгейту', 'Роман о мире без книг', 'fahrenheit_451.jpg'],
        ['Я, робот', 'Сборник рассказов о роботах', 'i_robot.jpg'],
        ['Старик и море', 'Повесть о борьбе старого рыбака', 'old_man_and_sea.jpg'],
        ['Вишневый сад', 'Пьеса о жизни русских помещиков', 'cherry_orchard.jpg']
    ];

    $stmt = $conn->prepare("INSERT INTO books (name, description, img) VALUES (:title, :desc, :img)");
    foreach ($books as $book) {
        $stmt->execute([':title' => $book[0], ':desc' => $book[1], ':img' => $book[2]]);
    }
    echo "Добавлены книги<br>";


    for ($i = 1; $i <= 10; $i++) {
        $conn->exec("INSERT INTO book_authors (book_id, author_id) VALUES ($i, $i)");
    }
    echo "Связаны книги с авторами<br>";


    $book_genres = [
        [1, 3], [1, 6],
        [2, 3],
        [3, 4],
        [4, 7],
        [5, 2],
        [6, 1], [6, 5],
        [7, 1], [7, 5],
        [8, 1], [8, 5],
        [9, 3], [9, 9],
        [10, 3]
    ];

    $stmt = $conn->prepare("INSERT INTO book_genres (book_id, genre_id) VALUES (:book_id, :genre_id)");
    foreach ($book_genres as $bg) {
        $stmt->execute([':book_id' => $bg[0], ':genre_id' => $bg[1]]);
    }
    echo "Связаны книги с жанрами<br>";

} catch(PDOException $e) {
    echo "Ошибка при заполнении данных: " . $e->getMessage();
}
