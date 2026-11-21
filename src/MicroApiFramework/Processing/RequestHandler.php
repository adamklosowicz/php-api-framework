<?php

namespace MicroApiFramework\Processing;

use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;
use MicroApiFramework\Routing\Controller;
use MicroApiFramework\Routing\Route;
use Throwable;
use TypeError;

class RequestHandler {

    public function __construct(
        protected ?Route $route,
        protected Request $request
    ) {}
    
    public function process(): Response {
        if ($this->route === null) {
            return new Response([
                    'error' => 'Invalid URI'
                ], HttpStatus::NOT_FOUND);
        }

        $controller = $this->route->getControllerObject($this->request);
        if (!$controller instanceof Controller) {
            return new Response([
                    'error' => 'Route controller has wrong implementation.'
                ], HttpStatus::INTERNAL_SERVER_ERROR);
        }
        $controller->validate($this->request, $this->route->getQueryParams(), $this->route->getBody());

        $handler = [$controller, $this->route->getAction()];
        $params = $this->route->getUriParams($this->request->getUri());
        try {
            $response = call_user_func_array($handler, array_values($params));
        } catch (TypeError $ex) {
            return new Response([
                    'error' => 'Invalid URI parameter values. Check your request!'
                ], HttpStatus::BAD_REQUEST);
        } catch (Throwable $ex) {
            return new Response([
                    'error' => 'Issue while processing the request.'
                ], HttpStatus::INTERNAL_SERVER_ERROR);
        }
        
        if (!$response instanceof Response) {
            return new Response([
                    'error' => 'Route handler must return a Response instance.'
                ], HttpStatus::INTERNAL_SERVER_ERROR);
        }
        return $response;
    }
}