<?php

use App\Database\Connection;
use App\Http\Request;
use App\Http\Response;
use App\Http\HttpException;
use App\Repository\Repositories;
use App\Router;
use App\View\SmartyView;

ini_set('display_errors', '1');
error_reporting(E_ALL);

$rootPath = dirname(__DIR__);
$config = require $rootPath . '/config/bootstrap.php';

$debug = $config['environment'] !== 'production';
$view = new SmartyView($rootPath, $debug);

try {
    $router = new Router();

    include_once __DIR__ . '/routes.php';

    $db = Connection::create($config['database']);
    $repositories = new Repositories($db);

    $response = $router->dispatch(Request::parseRequest(), $view, $repositories);
} catch (HttpException $e) {
    $response = new Response(
        $view->render('pages/error.tpl', ['code' => $e->getCode()]),
        $e->getCode(),
    );
} catch (Throwable $e) {
    echo 'An error occurred: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line ' . $e->getLine();
    error_log((string)$e);
    $response = new Response(
        $view->render('pages/error.tpl', ['code' => 500]),
        500,
    );
}

$response->send();
