<?php

namespace MicroApiFramework;

use MicroApiFramework\Config\Config;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Routing\Router;

class Application {

    public static function run(string $configYmlPath): void {
        $config = new Config($configYmlPath);
        $router = new Router($config);

        $response = $router->process(Request::capture());
        $response->send();
    }

}