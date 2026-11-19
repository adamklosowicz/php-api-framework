<?php

namespace MicroApiFramework\App;

use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;

class TestController {
    
    public function index(Request $request): Response {
        return new Response([
            'users' => []
        ], HttpStatus::NO_CONTENT);
    }

    public function show(Request $request, string $id): Response {
        return new Response([
            'users' => [$id]
        ]);
    }
}