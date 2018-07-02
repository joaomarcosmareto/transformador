<?php
namespace core\slim\middleware;

use core\util\{i18n, Cache, Logger, Mongo, Retorno};

class Initializer{

    private $redis_settings;
    private $logger_settings;
    private $mongo_settings;

    public function __construct(array $redis_settings, array $logger_settings, array $mongo_settings)
    {
        $this->redis_settings = $redis_settings;
        $this->logger_settings = $logger_settings;
        $this->mongo_settings = $mongo_settings;
    }
    /**
     * Initialize the EchoWays Framework
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        i18n::init();

        Cache::getInstance($this->redis_settings['host'], $this->redis_settings['port'], $this->redis_settings['pass']);
        Logger::getInstance($this->logger_settings, $request);

        Mongo::getInstance($this->mongo_settings);
        Retorno::init();
        $response = $next($request, $response);

        Cache::close();
        Logger::finaliza();
        return $response;
    }
}