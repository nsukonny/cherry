<?php

namespace App;

use App\Http\Request;
use App\Http\Response;
use App\Http\HttpException;
use App\View\SmartyView;
use JetBrains\PhpStorm\NoReturn;

final class Router
{

    private const VALID_METHODS = ['GET', 'POST'];
    private const PATTERN = '[a-z0-9]+(?:-[a-z0-9]+)*';

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
     * @return Response
     */
    public function dispatch(Request $request, SmartyView $view): Response
    {
        if (!in_array($request->method, self::VALID_METHODS, true)) {
            throw HttpException::methodNotAllowed(self::VALID_METHODS);
        }

        foreach ($this->routes as $route) {
            if ($route['path'] !== $request->path
                && preg_match($route['path'], $request->path) !== 1) {
                continue;
            }

            if ($route['method'] !== $request->method) {
                continue;
            }

            $explodedController = explode('@', $route['controller']);

            $controllerClass = 'App\\Controller\\' . $explodedController[0];
            $action = $explodedController[1] ?? 'index';

            $params = [];

            return (new $controllerClass($view))->{$action}($request, ...$params);
        }

        throw HttpException::notFound();
    }

    private function run(string $path): string
    {
        $parts = preg_split('#(\{[a-z]+\})#', $path, -1, PREG_SPLIT_DELIM_CAPTURE);
        $regex = '';

        foreach ($parts as $part) {
            $regex .= preg_match('#^\{([a-z]+)\}$#', $part, $m) === 1
                ? '(?<' . $m[1] . '>' . self::PATTERN . ')'
                : preg_quote($part, '#');
        }

        return '#^' . $regex . '$#';
    }
}
