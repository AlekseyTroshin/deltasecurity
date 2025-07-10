<?php

namespace app\controllers;

use app\models\Product;
use core\Controller;

/** @property Product $model*/
class ProductController extends Controller
{

    public function __construct(array $route = [])
    {
        parent::__construct($route);
        $action = $this->action;
        $this->$action();
    }

    public function view()
    {
        $book = $this->model->getBook($this->route['slug']);
        $this->getView(
            compact('book'),
            'product/view')->render();
    }

}