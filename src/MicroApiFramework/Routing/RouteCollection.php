<?php

namespace MicroApiFramework\Routing;

use Symfony\Component\Yaml\Yaml;

class RouteCollection {

    private array $routes = [];

    public function __construct(string $configYmlPath) {
        $config = Yaml::parseFile($configYmlPath);

        foreach ($config['routes'] ?? [] as $route) {
            $this->add($route['name'], new Route($route['method'], $route['path'], $route['controller'], $route['action']));
        }
    }

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