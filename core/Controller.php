<?php

namespace core;

abstract class Controller
{

    protected object $model;
    protected object $view;

    protected string $action;

    public function __construct(
        protected array $route = [],
    )
    {
        $this->getModel();
        $this->controllerAction();
    }

    public function controllerAction(): void
    {
        $action = $this->route['action'];

        if (method_exists($this, $action)) {
            $this->action = $action;
        } else {
            throw new \Exception("Метод {$this->route['controllers']}::{$this->route['action']} не 
            найден", 404);
        }
    }

    protected function getView(array $data, string $view): object
    {
        $view_file = VIEWS . "/{$view}.php";
        if (is_file($view_file)) {
            return new View($data, $view_file);
        } else {
            throw new \Exception("Файл views::{$view} не найден", 404);
        }
    }

    protected function getModel(): void
    {
        $model = MODELS . "\\{$this->route['controllers']}";
        if (class_exists($model)) {
            $this->model = new $model();
        }
    }

}