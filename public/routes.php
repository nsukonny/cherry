<?php
/**
 * Routes for the application.
 */

use App\Router;

$router = new Router();

$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/([a-zA-Z0-9]+)/', 'CategoryController@index');
$router->add('GET', '/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)/', 'ArticleController@index');

$router->run();
