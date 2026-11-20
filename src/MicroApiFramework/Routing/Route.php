<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;
use Throwable;
use TypeError;

class Route {

    public function __construct(
        private string $method,
        private string $path,
        private string $controller,
        private string $action
    ) {}

    public function getMethod(): string {
        return $this->method;
    }

    public function matches(string $method, string $uri): bool {
        if ($this->method !== strtoupper($method)) {
            return false;
        }
        $pattern = $this->getPathPattern();
        return (bool) preg_match($pattern, $uri);
    }

    public function processRequest(Request $request): Response {
        $controller = new $this->controller($request);
        if (!$controller instanceof Controller) {
            return new Response([
                    'error' => 'Route controller has wrong implementation.'
                ], HttpStatus::INTERNAL_SERVER_ERROR);
        }

        $handler = [$controller, $this->action];
        $parameters = $this->getParameters($request->getUri());
        try {
            $response = call_user_func_array($handler, array_values($parameters));
        } catch (TypeError $ex) {
            return new Response([
                    'error' => 'Invalid URI parameter values. Check your request!'
                ], HttpStatus::BAD_REQUEST);
        } catch (Throwable $ex) {
            return new Response([
                    'error' => 'Issue while processing the request.'
                ], HttpStatus::INTERNAL_SERVER_ERROR);
        }
        
        if (!$response instanceof Response) {
            return new Response([
                    'error' => 'Route handler must return a Response instance.'
                ], HttpStatus::INTERNAL_SERVER_ERROR);
        }
        return $response;
    }

    private function getParameters(string $uri): array {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $this->path, $parameterNames);
        $pattern = $this->getPathPattern();
        preg_match($pattern, $uri, $matches);
        array_shift($matches);
        return array_combine($parameterNames[1], $matches) ?: [];
    }

    private function getPathPattern(): string {
        $path = preg_quote($this->path, '#');
        $pattern = preg_replace('/\\\\\{[a-zA-Z_][a-zA-Z0-9_]*\\\\\}/', '([^/]+)', $path);
        return "#^$pattern$#";
    }
}