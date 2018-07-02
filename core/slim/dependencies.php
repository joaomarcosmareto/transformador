<?php
// DIC configuration
$container = $app->getContainer();
// view renderer
$container['renderer'] = function ($container) {
    $settings = $container->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['phpErrorHandler'] = function ($container) {
    return function ($request, $response, $error) use ($container) {
        $statusCode = $error->getCode() ? $error->getCode() : 500;

        $json = [
            //"statusCode"=> $statusCode,
            "message"   => $error->getMessage(),
            "file"      => $error->getFile(),
            "line"      => $error->getLine(),
            "trace"      => $error->getTraceAsString()
        ];

        return $container['response']->withStatus($statusCode)
        ->withHeader('Content-Type', 'Application/json')
        ->withJson($json, $statusCode);
    };
};

/**
 * Converte os Exceptions Genéricas dentro da Aplicação em respostas JSON
 */
$container['errorHandler'] = function ($container) {
    return new core\util\ErrorHandler();
};
/**
 * Converte os Exceptions de Erros 405 - Not Allowed
 */
$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response, $methods) use ($container) {
        return $container['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'Application/json')
            ->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
            ->withJson(["message" => "Method not Allowed; Method must be one of: " . implode(', ', $methods)], 405);
    };
};
/**
 * Converte os Exceptions de Erros 404 - Not Found
 */
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(['message' => 'Page not found']);
    };
};

$container['cache_settings']    = $container->get('settings')['redis'];
$container['logger_settings']   = $container->get('settings')['logger'];
$container['con_settings']      = $container->get('settings')['mongodb']['dev'];