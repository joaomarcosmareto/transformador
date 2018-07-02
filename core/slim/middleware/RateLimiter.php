<?php
namespace core\slim\middleware;

use core\util\{i18n, Cache, Logger};

class RateLimiter{

    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }


    /**
     * Limits how much the api can be called in a certain amount of time
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $deltaTempo = $this->settings['timeLimit'];
        $limiteContador = $this->settings['counterLimit'];

        if($deltaTempo !== -1){
            $keyBase = $_SERVER['REMOTE_ADDR'];

            $redis = Cache::getInstance();

            $t = time();//tempo em s

            $tRedis = $redis->get($keyBase."_T");
            if(empty($tRedis))
            {
                // o parametro do meio é o ttl da chave no cache redis
                $redis->setex($keyBase."_T", $deltaTempo, $t);
                $redis->setex($keyBase."_C", $deltaTempo, 1);
            }
            else
            {
                if($t > $tRedis+$deltaTempo)
                {
                    $redis->setex($keyBase."_T", $deltaTempo, $t);
                    $redis->setex($keyBase."_C", $deltaTempo, 1);
                }
                else
                {
                    $redis->incr($keyBase."_C");
                    if($redis->get($keyBase."_C") > $limiteContador)
                    {
                        $redis->close();
                        throw new \Exception("Too Many Requests", 429);
                    }
                }
            }
        }
        $response = $next($request, $response);
        return $response;
    }
}