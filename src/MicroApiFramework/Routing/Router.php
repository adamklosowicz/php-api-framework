<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;
use MicroApiFramework\Processing\RequestHandler;

class Router {

    public function __construct(
        private RouteCollection $routes
    ) {}

    public function process(Request $request): Response {
        $route = $this->routes->find($request->getMethod(), $request->getUri());
        $handler = new RequestHandler($route, $request);
        return $handler->process();
    }
}