<?php

namespace App;

use App\Http\Request;
use App\Http\Response;
use App\Http\HttpException;
use App\Repository\Repositories;
use App\View\SmartyView;

final class Router
{

    private array $routes = [];

    public function __construct()
    {
    }

    /**
     * Add a route to the router.
     *
     * @param string $method
     * @param string $path
     * @param string $controller
     *
     * @return void
     */
    public function add(string $method, string $path, string $controller): void
    {
        $this->routes[] = compact('method', 'path', 'controller');
    }

    /**
     * Dispatch request to Controller and Action
     *
     * @param Request $request
     * @param SmartyView $view
     * @param Repositories $repositories
     * @return Response
     */
    public function dispatch(Request $request, SmartyView $view, Repositories $repositories): Response
    {
        // HEAD is answered like GET; index.php drops the body.
        $method = $request->method === 'HEAD' ? 'GET' : $request->method;

        // Methods of routes whose path matched but method did not: 405 instead of 404.
        $allowed = [];

        foreach ($this->routes as $route) {
            if ('/' === $route['path']) {
                $route['path'] = '#^/$#';
            }

            if ($route['path'] !== $request->path
                && preg_match($route['path'], $request->path) !== 1) {
                continue;
            }

            if ($route['method'] !== $method) {
                $allowed[] = $route['method'];
                continue;
            }

            $explodedController = explode('@', $route['controller']);

            $controllerClass = 'App\\Controller\\' . $explodedController[0];
            $action = $explodedController[1] ?? 'index';

            $params = [];

            return (new $controllerClass($view, $repositories))->{$action}($request, ...$params);
        }

        if ($allowed !== []) {
            if (in_array('GET', $allowed, true)) {
                $allowed[] = 'HEAD';
            }

            throw HttpException::methodNotAllowed($allowed);
        }

        throw HttpException::notFound();
    }

}
