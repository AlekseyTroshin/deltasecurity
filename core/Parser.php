<?php

namespace core;


class Parser extends Model {

    private array $books;
    private array $authors;
    private array $genres;
    private array $bookAuthors;
    private array $bookGenres;

    public function getParesData()
    {
        $randPage = rand(1, 5);
        $url = "https://litlife.club/popular_books?page={$randPage}";

        $dom = new DOMDocument();
        @$dom->loadHTMLFile($url);
        $xpath = new DOMXPath($dom);
        $blocks = $xpath->query("//div[contains(@class, 'book card')]");


        $resultsParse = [];
        foreach ($blocks as $block) {
            $item = [];

            $img = $xpath->query(".//img[contains(@class, 'lazyload rounded')]", $block);
            $item['img'] = $img->length > 0 ? trim($img[0]->attributes['data-src']->value) : '';

            $title = $xpath->query(".//h3[contains(@class, 'break-words h5')]", $block);
            $item['title'] = $title->length > 0 ? trim($title[0]->nodeValue) : '';

            $authorsDOM = $xpath->query(".//*[contains(@class, 'author name')]", $block);
            $authors = [];
            foreach ($authorsDOM as $author) {
                $authors[] = $author->textContent;
            }
            $item['authors'] = $authors;

            $genresDOM = $xpath->query(".//*[contains(@class, 'author name')]", $block);
            $genresDOM = $genresDOM[0]->parentNode->nextElementSibling;

            $genres = explode(',', trim(ltrim(trim($genresDOM->textContent), 'Жанры:')));
            foreach ($genres as &$genre) {
                $genre = trim($genre);
            }
            $item['genres'] = $genres;

            $description = $xpath->query(".//div[contains(@class, 'mt-3')]", $block);
            $description = $description[0]->textContent ?? '';
            $item['description'] = trim(rtrim(trim($description), 'далее'));

            $resultsParse[] = $item;
        }

        $books = [];
        $authors = [];
        $genres = [];
        $bookAuthors = [];
        $bookGenres = [];
        $countAuthors = 1;
        $countGenres = 1;

        for ($i = 0; $i < count($resultsParse); $i++) {
            $book = [];
            $id = $i + 1;

            $book['id'] = $id;
            $book['name'] = $resultsParse[$i]['title'];
            $book['description'] = $resultsParse[$i]['description'];
            $book['img'] = $resultsParse[$i]['img'];

            foreach ($resultsParse[$i]['authors'] as $item) {
                if (!isset($authors[$item])) {
                    $authors[$item] = $countAuthors;
                    $countAuthors++;
                }

                $bookAuthors[] = ['book' => $id, 'author' => $authors[$item]];
            }

            foreach ($resultsParse[$i]['genres'] as $item) {
                if (!isset($genres[$item])) {
                    $genres[$item] = $countGenres;
                    $countGenres++;
                }

                $bookGenres[] = ['book' => $id, 'genre' => $genres[$item]];
            }

            $books[] = $book;
        }

        $this->books = $books;
        $this->authors = $authors;
        $this->genres = $genres;
        $this->bookAuthors = $bookAuthors;
        $this->bookGenres = $bookGenres;

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

    public function createWithDataTables()
    {

        $stmt = $this->db->prepare("INSERT INTO books (name, description, img) VALUES (:title, :desc, :img)");
        foreach ($this->books as $book) {
            $stmt->execute([':title' => $book['name'], ':desc' => $book['description'], ':img' => $book['img']]);
        }

        $stmt = $this->db->prepare("INSERT INTO authors (name) VALUES (:name)");
        foreach ($this->authors as $author) {
            $stmt->execute([':name' => $author]);
        }

        $stmt = $this->db->prepare("INSERT INTO genres (name) VALUES (:name)");
        foreach ($this->genres as $genre) {
            $stmt->execute([':name' => $genre]);
        }

        $stmt = $this->db->prepare("INSERT INTO book_genres (book_id, genre_id) VALUES (:book_id, :genre_id)");
        foreach ($this->bookGenres as $bg) {
            $stmt->execute([':book_id' => $bg['book'], ':genre_id' => $bg['genre']]);
        }

        $stmt = $this->db->prepare("INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :genre_id)");
        foreach ($this->bookAuthors as $ba) {
            $stmt->execute([':book_id' => $ba['book'], ':genre_id' => $ba['author']]);
        }
    }

}
