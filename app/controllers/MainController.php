<?php

namespace app\controllers;

use app\models\Main;
use core\Controller;

/** @property Main $model */
class MainController extends Controller
{

    protected array $authors;
    protected array $genres;
    protected string $booksSearch;
    protected string $booksFromAuthors;
    protected string $booksFromGenres;

    public function __construct(array $route = [])
    {
        parent::__construct($route);
        $this->authors = $this->model->getAuthors();
        $this->genres = $this->model->getGenres();
        $action = $this->action;
        $this->$action();
    }

    public function index()
    {
        $searchBooks = sessionsGet('searchBooks');
        $books = $this->model->getBooks($searchBooks);
        $authors = $this->authors;
        $genres = $this->genres;

        $this->getView(
            compact('books', 'authors', 'genres', 'searchBooks'),
            'main/index')->render();
    }

    public function search()
    {

        if (isset($_POST)) {
            $searchBooks = [];
            $search = post('search');

            $postStr = implode(',', $_POST);
            preg_match_all("#(?<=author-)\d+#", $postStr, $authors);
            preg_match_all("#(?<=genre-)\d+#", $postStr, $genres);

            if ($search) {
                $searchBooks['search'] =$search;
                sessionsSet('searchBooks', $searchBooks);
            } else {
                sessionsClear('searchBooks','search');
            }

            if (!empty($authors[0])) {
                $searchBooks['authors'] = implode(',', $authors[0]);
                sessionsSet('searchBooks', $searchBooks);
            } else {
                sessionsClear('searchBooks','authors');
            }

            if (!empty($genres[0])) {
                $searchBooks['genres'] = implode(',', $genres[0]);
                sessionsSet('searchBooks', $searchBooks);
            } else {
                sessionsClear('searchBooks','genres');
            }
        }

        redirect('/');
    }

    public function clearAll()
    {
        sessionsClear('searchBooks');
        redirect('/');
    }

}