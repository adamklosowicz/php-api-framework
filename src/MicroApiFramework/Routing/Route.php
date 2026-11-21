<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Http\Request;
use MicroApiFramework\Processing\Controller;

class Route {

    public function __construct(
        private string $method,
        private string $path,
        private string $controller,
        private string $action,
        private array $queryParams,
        private array $body
    ) {}

    public function getMethod(): string {
        return $this->method;
    }

    public function getControllerObject(Request $request): Controller {
        return new $this->controller($request);
    }

    public function getAction(): string {
        return $this->action;
    }

    public function getQueryParams(): array {
        return $this->queryParams;
    }

    public function getBody(): array {
        return $this->body;
    }

    public function matches(string $method, string $uri): bool {
        if ($this->method !== strtoupper($method)) {
            return false;
        }
        $pattern = $this->getPathPattern();
        return (bool) preg_match($pattern, $uri);
    }

    public function getUriParams(string $uri): array {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $this->path, $parameterNames);
        $pattern = $this->getPathPattern();
        preg_match($pattern, $uri, $matches);
        array_shift($matches);
        return array_combine($parameterNames[1], $matches) ?: [];
    }

    protected function getPathPattern(): string {
        $path = preg_quote($this->path, '#');
        $pattern = preg_replace('/\\\\\{[a-zA-Z_][a-zA-Z0-9_]*\\\\\}/', '([^/]+)', $path);
        return "#^$pattern$#";
    }
}