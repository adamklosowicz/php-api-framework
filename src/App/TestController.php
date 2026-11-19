<?php

namespace MicroApiFramework\App;

use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;

class TestController {
    
    public function __construct(
        private Request $request
    ) {}
    
    public function index(): Response {
        return new Response([
            'users' => []
        ], HttpStatus::NO_CONTENT);
    }

    public function show(string $id): Response {
        return new Response([
            'users' => [$id],
            'method' => $this->request->getMethod()
        ]);
    }
}