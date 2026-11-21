<?php

namespace MicroApiFramework\Processing;

use MicroApiFramework\Config\Config;
use MicroApiFramework\Exceptions\ResponseException;
use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Request;
use MicroApiFramework\Http\Response;
use MicroApiFramework\Processing\Validators\ProcessValidator;
use MicroApiFramework\Routing\Route;
use Throwable;
use TypeError;

class RequestHandler {

    public function __construct(
        protected Config $config,
        protected ?Route $route,
        protected Request $request
    ) {}
    
    public function process(): Response {
        try {
            return $this->validateAndDispatch();
        } catch (ResponseException $ex) {
            return $ex->getResponse($this->config->isDebug());
        }
    }

    protected function validateAndDispatch(): Response {
        ProcessValidator::validateRoute($this->route);

        try {
            $controller = $this->route->getControllerObject($this->request);
        } catch (Throwable $ex) {
            throw new ResponseException('Issue while initiating the controller.', HttpStatus::INTERNAL_SERVER_ERROR, $ex->getTrace()); 
        }
        ProcessValidator::validateController($controller);

        $controller->validate($this->request, $this->route->getQueryParams(), $this->route->getBody());

        $handler = [$controller, $this->route->getAction()];
        $params = $this->route->getUriParams($this->request->getUri());
        try {
            $response = call_user_func_array($handler, array_values($params));
        } catch (TypeError $ex) {
            throw new ResponseException('Invalid URI parameter values. Check your request!', HttpStatus::BAD_REQUEST, $ex->getTrace());
        } catch (Throwable $ex) {
            throw new ResponseException('Issue while processing the request.', HttpStatus::INTERNAL_SERVER_ERROR, $ex->getTrace());
        }
        
        ProcessValidator::validateResponse($response);
        return $response;
    }
}