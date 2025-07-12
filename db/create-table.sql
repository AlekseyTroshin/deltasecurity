CREATE DATABASE IF NOT EXISTS delta_security;

USE delta_security;

DROP TABLE IF EXISTS book_authors;
DROP TABLE IF EXISTS book_genres;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS genres;

CREATE TABLE IF NOT EXISTS books (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    img VARCHAR(255),
    created_at TIMESTAMP default CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS authors (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP default CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS genres (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP default CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS book_authors (
    book_id INT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (book_id) REFERENCES books (id),
    FOREIGN KEY (author_id) REFERENCES authors (id)
);

CREATE TABLE IF NOT EXISTS book_genres (
     book_id INT UNSIGNED NOT NULL,
     genre_id INT UNSIGNED NOT NULL,
     FOREIGN KEY (book_id) REFERENCES books (id),
     FOREIGN KEY (genre_id) REFERENCES genres (id)
);


INSERT INTO genres (name) VALUES
      ('Фантастика'),
      ('Детектив'),
      ('Роман'),
      ('Фэнтези'),
      ('Научная литература'),
      ('Исторический роман'),
      ('Ужасы'),
      ('Приключения'),
      ('Биография'),
      ('Поэзия');


INSERT INTO authors (name) VALUES
        ('Лев Николаевич Толстой'),
        ('Фёдор Михайлович Достоевский'),
        ('Джоан Роулинг'),
        ('Стивен Кинг'),
        ('Агата Кристи'),
        ('Джордж Оруэлл'),
        ('Рэй Брэдбери'),
        ('Айзек Азимов'),
        ('Эрнест Хемингуэй'),
        ('Антон Павлович Чехов');

INSERT INTO books (name, description, img) VALUES
        ('Война и мир', 'Эпический роман о войне 1812 года', '/assets/img/books/war_and_peace.jpg'),
        ('Преступление и наказание', 'Философский роман о преступлении и его последствиях', '/assets/img/books/crime_and_punishment.jpg'),
        ('Гарри Поттер и философский камень', 'Первая книга о юном волшебнике', '/assets/img/books/harry_potter_1.jpg'),
        ('Оно', 'Роман ужасов о древнем зле', '/assets/img/books/it.jpg'),
        ('Убийство в Восточном экспрессе', 'Знаменитый детектив Эркюля Пуаро', '/assets/img/books/orient_express.jpg'),
        ('1984', 'Антиутопия о тоталитарном обществе', '/assets/img/books/1984.jpg'),
        ('451 градус по Фаренгейту', 'Роман о мире без книг', '/assets/img/books/fahrenheit_451.jpg'),
        ('Я, робот', 'Сборник рассказов о роботах', '/assets/img/books/i_robot.jpg'),
        ('Старик и море', 'Повесть о борьбе старого рыбака', '/assets/img/books/old_man_and_sea.jpg'),
        ('Вишневый сад', 'Пьеса о жизни русских помещиков', '/assets/img/books/cherry_orchard.jpg');


INSERT INTO book_authors (book_id, author_id) VALUES
      (1, 1), (2, 2), (3, 3), (4, 4), (5, 5),
      (6, 6), (7, 7), (8, 8), (9, 9), (10, 10);


INSERT INTO book_genres (book_id, genre_id) VALUES
        (1, 3), (1, 6),
        (2, 3),
        (3, 4),
        (4, 7),
        (5, 2),
        (6, 1), (6, 5),
        (7, 1), (7, 5),
        (8, 1), (8, 5),
        (9, 3), (9, 9),
        (10, 3);