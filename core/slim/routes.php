<?php
use Slim\Http\Request;
use Slim\Http\Response;
use core\slim\middleware\{Initializer, RateLimiter, Authorizer};
use core\util\auth\Auth;
// Routes

$app->post('/login', '\core\controllers\UsuarioPublicAPIController:login')
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));

$app->post('/refreshAuth', '\core\controllers\RefreshAuth:refresh')
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));

$app->post('/logout', '\core\controllers\UsuarioPublicAPIController:logout')
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Authorizer())
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));


$app->group('/public', function(){
    $this->post('/registrar', '\core\controllers\UsuarioPublicAPIController:cadastrar');
    $this->post('/confirmarRegistro', '\core\controllers\UsuarioPublicAPIController:confirmarCadastro');
    $this->post('/recuperarSenha', '\core\controllers\UsuarioPublicAPIController:recuperarSenha');
    $this->post('/alterarSenha', '\core\controllers\UsuarioPublicAPIController:alterarSenha');
})
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));

$app->group('/adm/igreja', function(){
    $this->post('/listar', '\core\controllers\NegocioController:listar');
    $this->post('/salvar', '\core\controllers\NegocioController:salvar');
    $this->post('/selecionar', '\core\controllers\NegocioController:selecionar');
    $this->post('/remover', '\core\controllers\NegocioController:desativar');
})
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Authorizer($container->get('settings')['requestRate']))
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));

$app->group('/adm/igreja/menu', function(){
    $this->post('/listar', '\core\controllers\MenuController:listar');
    $this->post('/salvar', '\core\controllers\MenuController:salvar');
    $this->post('/selecionar', '\core\controllers\MenuController:selecionar');
    $this->post('/remover', '\core\controllers\MenuController:remover');
})
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Authorizer($container->get('settings')['requestRate']))
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));

$app->group('/adm/igreja/categoria', function(){
    $this->post('/salvar', '\core\controllers\CategoriaController:salvar');
})
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Authorizer($container->get('settings')['requestRate']))
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));

$app->group('/adm/igreja/conteudo', function(){
    $this->post('/listar', '\core\controllers\ConteudoController:listar');
    $this->post('/selecionar', '\core\controllers\ConteudoController:selecionar');
    $this->post('/salvar', '\core\controllers\ConteudoController:salvar');
    $this->post('/remover', '\core\controllers\ConteudoController:remover');
    $this->post('/upload', '\core\controllers\ConteudoController:upload');
    $this->post('/browser', '\core\controllers\ConteudoController:browser');
    $this->post('/dirtree', '\core\controllers\ConteudoController:dirtree');
    $this->post('/createdir', '\core\controllers\ConteudoController:createdir');
    $this->post('/deletedir', '\core\controllers\ConteudoController:deletedir');
    $this->post('/deletefile', '\core\controllers\ConteudoController:deletefile');
    //$this->post('/thumb', '\core\controllers\ConteudoController:thumb');
})
->add(new RateLimiter($container->get('settings')['requestRate']))
->add(new Authorizer($container->get('settings')['requestRate']))
->add(new Initializer(
    $container['cache_settings'],
    $container['logger_settings'],
    $container['con_settings']
));


// $app->get('/[{name}]', function (Request $request, Response $response, array $args) {
//     // Sample log message
//     // $this->logger->info("rota: '/'" . $args['name']);
//     // var_dump("3");
//     // Logger::info("rota: '/'" . $args['name']);
//     // var_dump("ALALA");
//     // throw new Exception("Too Many Requests", 429);
//     // Render index view
//     // return $this->renderer->render($response, 'index.phtml', $args);
//     // return $response->withJson($args, 200);
//     // $usuario = new Usuario();
//     // $response = $response->withHeader('Authorization', 'Bearer '. Auth::generateToken($usuario));
//     $response->getBody()->write("Route 1\n");
//     return $response;
// })
// ->add(new Authorizer())
// ->add(new RateLimiter($container->get('settings')['requestRate']))
// ->add(new Initializer($container->get('settings')['redis'], $container->get('settings')['logger']));
