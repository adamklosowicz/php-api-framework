<?php

namespace MicroApiFramework\Exceptions;

use Exception;
use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Response;

class ResponseException extends Exception {
    
    public function __construct(
        private string $errorMsg = 'Request cannot be processed.',
        private int $statusCode = HttpStatus::INTERNAL_SERVER_ERROR,
        private ?string $debugMsg = null,
        private ?array $trace = null
    ) {}
    
    public function getResponse(bool $isDebug): Response {
        $responseArr = ['error' => $this->errorMsg];
        if ($isDebug) {
            if (!is_null($this->debugMsg)) {
                $responseArr['debug'] = $this->debugMsg;
            }
            if (!is_null($this->trace)) {
                $responseArr['trace'] = $this->trace;
            }
        }
        return new Response($responseArr, $this->statusCode);
    }
}