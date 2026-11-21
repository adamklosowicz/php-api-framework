<?php

namespace MicroApiFramework\Config;

use MicroApiFramework\Routing\Route;

class RouteCollection {

    private array $routes = [];

    public function getAll(): array {
        return $this->routes;
    }

    public function add(string $name, Route $route): void {
        $this->routes[$name] = $route;
    }

    public function find(string $method, string $uri): ?Route {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                return $route;
            }
        }
        return null;
    }
}