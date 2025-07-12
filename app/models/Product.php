<?php

namespace app\models;

use core\Model;
use PDO;

class Product extends Model
{
    public function getBook(string $slug): array
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
            WHERE b.id = $slug
            GROUP BY b.id
        ";

        return $this->db->query($query)->fetch(PDO::FETCH_ASSOC);
    }
}