<?php

namespace MicroApiFramework\Routing;

use Exception;
use MicroApiFramework\Http\Request;

class Controller {

    public function __construct(
        protected Request $request
    ) {}

    public function validate(Request $request, array $queryParams, array $bodyParams): void {
        if ($request->getMethod() == 'GET') {
            foreach ($queryParams as $field) {
                if (isset($field['required']) && $field['required'] == 'true' && !in_array($field['name'], array_keys($request->getQueryParams()))) {
                    throw new Exception('Missing field "' . $field['name'] . '"');
                }
                if (isset($field['min']) && in_array($field['name'], array_keys($request->getQueryParams())) && $request->getQueryParams()[$field['name']] < $field['min']) {
                    throw new Exception('Value too small for field "' . $field['name'] . '"');
                }
                if (isset($field['max']) && in_array($field['name'], array_keys($request->getQueryParams())) && $request->getQueryParams()[$field['name']] > $field['max']) {
                    throw new Exception('Value too big for field "' . $field['name'] . '"');
                }
            }
        }
    }
}