<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;

class Router {

    public function __construct(
        private RouteCollection $routes
    ) {}

    public function process(Request $request): Response {
        $route = $this->routes->find($request->getMethod(), $request->getUri());
        if ($route === null) {
            return new Response([
                    'error' => 'Not Found'
                ], HttpStatus::NOT_FOUND);
        }
        return $route->processRequest($request);
    }
}