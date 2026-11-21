<?php

namespace MicroApiFramework\Processing;

use MicroApiFramework\Exceptions\ResponseException;
use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;

class Controller {

    public function __construct(
        protected Request $request
    ) {}

    public function validate(Request $request, array $queryParams, array $bodyParams): void {
        if ($request->getMethod() == 'GET') {
            foreach ($queryParams as $field) {
                if (isset($field['required']) && $field['required'] == 'true' && !in_array($field['name'], array_keys($request->getQueryParams()))) {
                    throw new ResponseException("Missing field '{$field['name']}'", HttpStatus::BAD_REQUEST);
                }
                if (isset($field['min']) && in_array($field['name'], array_keys($request->getQueryParams())) && $request->getQueryParams()[$field['name']] < $field['min']) {
                    throw new ResponseException("Value too small for field '{$field['name']}'", HttpStatus::BAD_REQUEST);
                }
                if (isset($field['max']) && in_array($field['name'], array_keys($request->getQueryParams())) && $request->getQueryParams()[$field['name']] > $field['max']) {
                    throw new ResponseException("Value too big for field '{$field['name']}'", HttpStatus::BAD_REQUEST);
                }
            }
        }
    }
}