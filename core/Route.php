<?php

namespace core;

class Route
{

    protected array $route;

    public function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false === str_contains($params[0], '=')) {
                return rtrim($params[0], '/');
            }
        }

        return '';
    }

    public function dispatch(array $routes, string $url): array
    {
        $url = $this->removeQueryString($url);

        if ($this->matchRoute($routes, $url)) {
            $controllerPath = CONTROLLERS . '\\'
                . $this->upperCamelCase($this->route['controllers']) . 'Controller';
            if (class_exists($controllerPath)) {
                $this->route['classController'] = $controllerPath;

                return $this->route;
            } else {
                throw new \Exception("Контроллер {$controllerPath} не найден", 404);
            }
        } else {
            throw new \Exception("Страница не найден", 404);
        }
    }

    public function matchRoute(array $routes, string $url): bool
    {
        foreach ($routes as $route) {
            if (preg_match("#{$route['pattern']}#", $url, $matches)) {

                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route['controller'][$k] = $v;
                    }
                }



                $this->route = $route['controller'];

                return true;
            }
        }

        return false;
    }

    protected static function upperCamelCase($name): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    public function getRoute(): array
    {
        return $this->route;
    }
}