<?php
//
use \Tamtamchik\SimpleFlash\Flash;
use function Tamtamchik\SimpleFlash\flash;
use DI\ContainerBuilder;

if (!session_id()) @session_start();

require "../vendor/autoload.php";

$containerBuilder=new ContainerBuilder;
$container=$containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/createUser', ['App\controllers\AdminUserController', 'store']);
    $r->addRoute('GET', '/home', ['App\controllers\HomeController', 'index']);
    $r->addRoute('GET', '/show_login', ['App\controllers\AdminUserController', 'showLogin']);
    $r->addRoute('GET', '/show_register', ['App\controllers\AdminUserController', 'showRegister']);
    $r->addRoute('GET', '/exit_login', ['App\controllers\AdminUserController', 'exitLogin']);
    $r->addRoute('POST', '/login', ['App\controllers\AdminUserController', 'login']);

    $r->addRoute('GET', '/create_user', ['App\controllers\AdminUserController', 'createUser']);
    $r->addRoute('POST', '/store_user', ['App\controllers\AdminUserController', 'storeUser']);
    $r->addRoute('GET', '/edit', ['App\controllers\AdminUserController', 'showUser']);
    $r->addRoute('POST', '/update_user', ['App\controllers\AdminUserController', 'updateUser']);
    $r->addRoute('GET', '/security', ['App\controllers\AdminUserController', 'showSecurity']);
    $r->addRoute('POST', '/update_security', ['App\controllers\AdminUserController', 'updateSecurity']);
    $r->addRoute('GET', '/status', ['App\controllers\AdminUserController', 'showStatus']);
    $r->addRoute('POST', '/update_status', ['App\controllers\AdminUserController', 'updateStatus']);
    $r->addRoute('GET', '/show_media', ['App\controllers\AdminUserController', 'showMedia']);
    $r->addRoute('POST', '/update_media', ['App\controllers\AdminUserController', 'updateMedia']);
    $r->addRoute('GET', '/delete_user', ['App\controllers\AdminUserController', 'deleteUser']);


    $r->addRoute('GET', '/about', ['App\controllers\HomeController', 'about']);
    $r->addRoute('GET', '/factory', ['App\controllers\UserController', 'factory']);
    $r->addRoute('GET', '/verification', ['App\controllers\HomeController', 'emailVerification']);
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\d+}', ['App\controllers\HomeController', 'index']);
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $controller = new $handler[0];
        $cont=$container->call($routeInfo[1], $routeInfo[2]);
        call_user_func([$controller, $handler[1]], $vars);
        break;
}





