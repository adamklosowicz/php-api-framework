<?php

namespace MicroApiFramework\Processing\Validators;

use MicroApiFramework\Exceptions\ResponseException;
use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Response;
use MicroApiFramework\Processing\Controller;
use MicroApiFramework\Routing\Route;

class ProcessValidator {

    static function validateRoute(?Route $route): void {
        if (is_null($route)) {
            throw new ResponseException('Invalid URI', HttpStatus::NOT_FOUND);
        }
    }

    static function validateController(mixed $controller): void {
        if (!$controller instanceof Controller) {
            throw new ResponseException('Route controller has wrong implementation.');
        }
    }

    static function validateResponse(mixed $response): void {
        if (!$response instanceof Response) {
            throw new ResponseException('Route handler must return a Response instance.');
        }
    }
}