<?php
/**
 * Routes for the application.
 */

use App\Router;

$router = new Router();

$router->add( 'GET', '/', 'HomeController' );
$router->add( 'GET', '/([a-zA-Z0-9]+)/', 'CategoryController' );
$router->add( 'GET', '/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)/', 'ArticleController' );

$router->run();
