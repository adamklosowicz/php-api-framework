<?php

namespace ExampleApp\Controllers;

use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Response;
use MicroApiFramework\Routing\Controller;

class ExampleController extends Controller {
    
    public function index(): Response {
        return new Response([
            'users' => []
        ], HttpStatus::NO_CONTENT);
    }

    public function show(int $id): Response {
        return new Response([
            'users' => [$id],
            'method' => $this->request->getMethod()
        ]);
    }
}