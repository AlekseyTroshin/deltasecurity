<?php

namespace core;

class App
{

    protected Route $route;

    public function __construct()
    {
        session_start();
        new ErrorHandler();
        $this->route = new Route();

        $this->init();
    }

    protected function init(): void
    {
        $url = trim(urldecode($_SERVER['QUERY_STRING']), '/');
        $routes = require_once CONFIG . '/routes.php';
        $classObject = $this->route->dispatch($routes, $url);

        new $classObject['classController'](
            $this->route->getRoute()
        );
    }

}