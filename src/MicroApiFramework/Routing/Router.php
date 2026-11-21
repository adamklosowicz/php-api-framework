<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Config\Config;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;
use MicroApiFramework\Processing\RequestHandler;

class Router {

    public function __construct(
        private Config $config
    ) {}

    public function process(Request $request): Response {
        $route = $this->config->getRoutes()->find($request->getMethod(), $request->getUri());
        $handler = new RequestHandler($this->config, $route, $request);
        return $handler->process();
    }
}