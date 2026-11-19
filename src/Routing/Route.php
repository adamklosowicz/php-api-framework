<?php

namespace MicroApiFramework\Routing;

class Route {

    public function __construct(
        private string $method,
        private string $uri,
        private array $handler
    ) {}

    public function getMethod(): string {
        return $this->method;
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getHandler(): array {
        $controller = new $this->handler[0]();
        return [$controller, $this->handler[1]];
    }

    public function matches(string $method, string $uri): bool {
        if ($this->method !== strtoupper($method)) {
            return false;
        }
        $pattern = preg_quote($this->uri, '#');
        $pattern = preg_replace(
            '/\\\\\{[a-zA-Z_][a-zA-Z0-9_]*\\\\\}/',
            '[^/]+',
            $pattern
        );
        return (bool) preg_match("#^$pattern$#", $uri);
    }

    public function getParameters(string $uri): array {
        preg_match_all(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            $this->uri,
            $parameterNames
        );
        $pattern = preg_quote($this->uri, '#');
        $pattern = preg_replace(
            '/\\\\\{[a-zA-Z_][a-zA-Z0-9_]*\\\\\}/',
            '([^/]+)',
            $pattern
        );
        preg_match("#^$pattern$#", $uri, $matches);
        array_shift($matches);
        return array_combine($parameterNames[1], $matches) ?: [];
    }
}