<?php

use App\Database\Connection;
use App\Http\Request;
use App\Http\Response;
use App\Http\HttpException;
use App\Repository\Repositories;
use App\Router;
use App\View\SmartyView;

$rootPath = dirname(__DIR__);
$config = require $rootPath . '/config/bootstrap.php';

$debug = $config['environment'] !== 'production';
$view = new SmartyView($rootPath, $debug);
$request = Request::parseRequest();

try {
    $router = new Router();

    include_once __DIR__ . '/routes.php';

    $db = Connection::create($config['database']);
    $repositories = new Repositories($db);

    $response = $router->dispatch($request, $view, $repositories);
} catch (HttpException $e) {
    $response = new Response(
        $view->render(404 === $e->status() ? 'pages/404.tpl' : 'pages/error.tpl', [
            'code' => $e->status(),
            'title' => $e->getMessage() . ' — Cherry',
            'noindex' => true,
            'categories' => isset($repositories) ? $repositories->categories()->findAll() : [],
        ]),
        $e->status(),
        $e->headers,
    );
} catch (Throwable $e) {
    error_log((string)$e);
    $response = new Response(
        $view->render('pages/error.tpl', [
            'code' => 500,
            'title' => 'Ошибка сервера — Cherry',
            'noindex' => true,
        ]),
        500,
    );
}

$response->send(withBody: $request->method !== 'HEAD');
