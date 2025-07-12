<?php

namespace app\controllers;

use app\models\Parser;
use core\Controller;
use DOMDocument;
use DOMXPath;

/** @property Parser $model*/
class ParserController extends Controller
{

    private array $books;
    private array $authors;
    private array $genres;
    private array $bookAuthors;
    private array $bookGenres;

    public function __construct(
        protected array $route = [],
    )
    {
        parent::__construct($route);
        $action = $this->action;
        $this->$action();
    }

    public function index()
    {
        $this->model->clearAllTables();
        $this->getParesData();
        $this->model->createWithDataTables(
            $this->books,
            $this->authors,
            $this->genres,
            $this->bookAuthors,
            $this->bookGenres,
        );

        if (isset($_POST) && isAjax()) {
            $data = [
                'books' => $this->model->getBooks(),
                'authors' => $this->model->getAuthors(),
                'genres' => $this->model->getGenres()
            ];

            exit(json_encode($data));
        }
    }

    public function getParesData()
    {
        $randPage = rand(1, 5);
        $url = "https://litlife.club/popular_books?page={$randPage}";
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTMLFile($url);
        libxml_use_internal_errors(false);
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

        $authors = array_keys($authors);
        $genres = array_keys($genres);

        $this->books = $books;
        $this->authors = $authors;
        $this->genres = $genres;
        $this->bookAuthors = $bookAuthors;
        $this->bookGenres = $bookGenres;

    }


}
