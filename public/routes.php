<?php
/**
 * Routes for the application.
 *
 * @var Router $router
 */

use App\Router;

$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/([a-zA-Z0-9]+)/', 'CategoryController@index');
$router->add('GET', '/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)/', 'PostController@index');
