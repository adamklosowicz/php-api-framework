<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;

class Router {

    public function __construct(
        private RouteCollection $routes
    ) {}

    public function dispatch(Request $request): Response {
        $route = $this->routes->find($request->getMethod(), $request->getUri());

        if ($route === null) {
            return new Response(['error' => 'Not Found'], 404);
        }

        $handler = $route->getHandler($request);

        $parameters = $route->getParameters($request->getUri());

        $response = call_user_func_array($handler, array_values($parameters));

        if (!$response instanceof Response) {
            throw new \LogicException('Route handler must return a Response instance.');
        }
        return $response;
    }
}