<?php

namespace app\models;

use core\Model;
use PDO;

class Parser extends Model
{


    public function createWithDataTables(
        array $books,
        array $authors,
        array $genres,
        array $bookAuthors,
        array $bookGenres,
    )
    {

        $stmt = $this->db->prepare("INSERT INTO books (name, description, img) VALUES (:title, :desc, :img)");
        foreach ($books as $book) {
            $stmt->execute([':title' => $book['name'], ':desc' => $book['description'], ':img' => $book['img']]);
        }

        $stmt = $this->db->prepare("INSERT INTO authors (name) VALUES (:name)");
        foreach ($authors as $author) {
            $stmt->execute([':name' => $author]);
        }

        $stmt = $this->db->prepare("INSERT INTO genres (name) VALUES (:name)");
        foreach ($genres as $genre) {
            $stmt->execute([':name' => $genre]);
        }

        $stmt = $this->db->prepare("INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :genre_id)");
        foreach ($bookAuthors as $ba) {
            $stmt->execute([':book_id' => $ba['book'], ':genre_id' => $ba['author']]);
        }

        $stmt = $this->db->prepare("INSERT INTO book_genres (book_id, genre_id) VALUES (:book_id, :genre_id)");
        foreach ($bookGenres as $bg) {
            $stmt->execute([':book_id' => $bg['book'], ':genre_id' => $bg['genre']]);
        }

    }

    public function clearAllTables(): void
    {

        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->exec("TRUNCATE TABLE book_genres");
        $this->db->exec("TRUNCATE TABLE book_authors");
        $this->db->exec("TRUNCATE TABLE books");
        $this->db->exec("TRUNCATE TABLE authors");

        $this->db->exec("TRUNCATE TABLE genres");
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

    }

    public function getBooks(): array
    {
        $query = "
            SELECT
                b.id AS id,
                b.name AS name,
                b.description AS description,
                b.img AS img,
                GROUP_CONCAT(DISTINCT g.name) AS genres,
                GROUP_CONCAT(DISTINCT a.name) AS authors
            FROM books AS b
            LEFT JOIN book_genres AS bg ON b.id = bg.book_id
            LEFT JOIN genres AS g ON g.id = bg.genre_id
            LEFT JOIN book_authors AS ba ON b.id = ba.book_id
            LEFT JOIN authors AS a ON a.id = ba.author_id
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

    private function getFetchAll(string $query): array
    {
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }


}