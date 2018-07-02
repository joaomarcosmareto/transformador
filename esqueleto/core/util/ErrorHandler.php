<?php

namespace core\util;

use Psr\Http\Message\ ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
// use Monolog\Logger;
use core\util\Logger;

final class ErrorHandler extends \Slim\Handlers\Error
{
    // protected $logger;

    public function __construct()
    {
        // $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        // Log the message
        // $this->logger->critical($exception->getMessage());
        Logger::setFluxo($exception->getMessage());
        Logger::finaliza();

        $statusCode = $exception->getCode() ? $exception->getCode() : 500;

        $json = [
            //"statusCode"=> $statusCode,
            "message"   => $exception->getMessage(),
            "file"      => $exception->getFile(),
            "line"      => $exception->getLine(),
            "trace"      => $exception->getTraceAsString()
        ];

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-type', 'application/json')
            ->withJson($json, $statusCode);
    }
}