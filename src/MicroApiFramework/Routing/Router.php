<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;
use MicroApiFramework\Processing\Driver;

class Router {

    public function __construct(
        private RouteCollection $routes
    ) {}

    public function process(Request $request): Response {
        $route = $this->routes->find($request->getMethod(), $request->getUri());
        $driver = new Driver($route, $request);
        return $driver->process();
    }
}