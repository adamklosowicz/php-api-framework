<?php

namespace MicroApiFramework\Routing;

use Symfony\Component\Yaml\Yaml;

class RouteCollection {

    private array $routes = [];

    public function __construct(string $configYmlPath) {
        $config = Yaml::parseFile($configYmlPath);

        foreach ($config['routes'] ?? [] as $route) {
            $controller = $route['controller'];
            $action = $route['action'];
            $handler = [$controller, $action];

            $this->add(new Route($route['method'], $route['path'], $handler));
        }
    }

    public function add(Route $route): void {
        $this->routes[] = $route;
    }

    public function getAll(): array {
        return $this->routes;
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