<?php

namespace app\models;

use core\Model;
use PDO;

class Main extends Model
{

    public function getBooks(array|NULL $param): array
    {
        $search = '';
        $authors = '';
        $genres = '';

        if (is_array($param)) {
            extract($param);
        }

        $and = match (true) {
            !empty($authors) && !empty($genres) => "AND b.id IN (
                SELECT
                    book_id
                FROM book_authors
                WHERE author_id IN ($authors)
                AND b.id IN (
                    SELECT
                        book_id
                    FROM book_genres
                    WHERE genre_id IN($genres)
                )
            )",
            !empty($authors) => "AND b.id IN (
                SELECT
                    book_id
                FROM book_authors
                WHERE author_id IN ($authors)
            )",
            !empty($genres) => "AND b.id IN (
                SELECT
                    book_id
                FROM book_genres
                WHERE genre_id IN($genres)
            )",
            default => ''
        };

        $query = "
            SELECT
                b.id AS id,
                b.name AS name,
                b.description AS description,
                b.img AS img,
                GROUP_CONCAT(DISTINCT g.name) AS genres,
                GROUP_CONCAT(DISTINCT CONCAT(a.name, ' ', a.surname, ' ', a.patronymic)) AS authors
            FROM books AS b
            LEFT JOIN book_genres AS bg ON b.id = bg.book_id
            LEFT JOIN genres AS g ON g.id = bg.genre_id
            LEFT JOIN book_authors AS ba ON b.id = ba.book_id
            LEFT JOIN authors AS a ON a.id = ba.author_id
            WHERE b.name LIKE '%$search%' $and
            GROUP BY b.id
        ";

        return $this->getFetchAll($query);
    }

    public function getAuthors(): array
    {
        $query = "
            SELECT *
            FROM authors
        ";

        return $this->getFetchAll($query);
    }

    public function getGenres(): array
    {
        $query = "
            SELECT *
            FROM genres
        ";

        return $this->getFetchAll($query);
    }

    public function getFetchAll(string $query): array
    {
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

}