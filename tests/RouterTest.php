<?php

declare(strict_types=1);

use App\Http\HttpException;
use App\Http\Request;
use App\Repository\Repositories;
use App\Router;
use App\View\SmartyView;

/**
 * Router with the real routes. Only cases that end before a controller runs,
 * so no database is needed (Repositories creates repositories lazily).
 */
function routerForTest(): array
{
    $router = new Router();
    require dirname(__DIR__) . '/public/routes.php';

    return [
        $router,
        new SmartyView(sys_get_temp_dir()),
        new Repositories(new PDO('sqlite::memory:')),
    ];
}

test('Router: unknown path is 404', function (): void {
    [$router, $view, $repositories] = routerForTest();

    foreach (['/a/b/c', '/Morozostoykie', '/a--b', '/../etc'] as $path) {
        $e = assertThrows(HttpException::class, fn () => $router->dispatch(new Request('GET', $path), $view, $repositories));
        assertSame(404, $e->status(), $path);
    }
});

test('Router: known path with wrong method is 405 with Allow', function (): void {
    [$router, $view, $repositories] = routerForTest();

    foreach (['POST', 'PUT', 'DELETE'] as $method) {
        $e = assertThrows(HttpException::class, fn () => $router->dispatch(new Request($method, '/morozostoykie'), $view, $repositories));
        assertSame(405, $e->status(), $method);
        assertSame(['Allow' => 'GET, HEAD'], $e->headers, $method);
    }
});
