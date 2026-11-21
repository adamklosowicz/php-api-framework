<?php

namespace MicroApiFramework\Config;

use Exception;
use MicroApiFramework\Routing\Route;
use Symfony\Component\Yaml\Yaml;

class Config {

    private bool $debug = false;
    private ?RouteCollection $routes = null;

    public function __construct(string $configYmlPath) {
        $config = Yaml::parseFile($configYmlPath);

        if (isset($config['debug'])) {
            $this->debug = (bool) $config['debug'];
        }
        $this->routes = new RouteCollection();
        if (isset($config['routes'])) {
            foreach ($config['routes'] ?? [] as $route) {
                $queryParams = $route['queryParams'] ?? [];
                $body = $route['body'] ?? [];
                $this->routes->add($route['name'], new Route($route['method'], $route['path'], $route['controller'], $route['action'], $queryParams, $body));
            }
        } else {
            throw new Exception("Routes are not defined in config.");
        }
    }

    public function isDebug(): bool {
        return $this->debug;
    }

    public function getRoutes(): RouteCollection {
        return $this->routes;
    }
}