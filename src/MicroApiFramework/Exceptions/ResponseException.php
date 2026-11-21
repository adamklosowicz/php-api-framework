<?php

namespace MicroApiFramework\Exceptions;

use Exception;
use MicroApiFramework\Http\HttpStatus;
use MicroApiFramework\Http\Response;

class ResponseException extends Exception {
    
    public function __construct(
        private string $erroMsg,
        private int $statusCode = HttpStatus::INTERNAL_SERVER_ERROR,
        private ?array $trace = null
    ) {}
    
    public function getResponse(bool $isDebug): Response {
        $responseArr = ['error' => $this->erroMsg];
        if ($isDebug && !is_null($this->trace)) {
            $responseArr['trace'] = $this->trace;
        }
        return new Response($responseArr, $this->statusCode);
    }
}