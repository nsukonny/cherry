<?php
/**
 * Routes for the application.
 *
 * @var Router $router
 */

use App\Router;

$slug = '[a-z0-9]+(?:-[a-z0-9]+)*';

$router->add('GET', '/', 'HomeController@index');
$router->add('GET', "#^/{$slug}$#", 'CategoryController@index');
$router->add('GET', "#^/{$slug}/{$slug}$#", 'PostController@index');
