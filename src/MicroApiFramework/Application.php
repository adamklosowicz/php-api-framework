<?php

namespace MicroApiFramework;

use MicroApiFramework\Http\Request;
use MicroApiFramework\Routing\Router;
use MicroApiFramework\Routing\RouteCollection;

class Application {

    public static function run(string $configYmlPath): void {
        $routeCollection = new RouteCollection($configYmlPath);
        $router = new Router($routeCollection);

        $response = $router->process(Request::capture());
        $response->send();
    }

}