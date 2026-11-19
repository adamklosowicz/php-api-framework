<?php

namespace MicroApiFramework\Http;

class Request {
    
    public function __construct(
        private string $method,
        private string $uri,
        private array $queryParams,
        private array $body
    ) {}

    public static function capture(): self {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        return new self(
            $_SERVER['REQUEST_METHOD'],
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            $_GET,
            $body
        );
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getQueryParams(): array {
        return $this->queryParams;
    }

    public function getBody(): array {
        return $this->body;
    }
}