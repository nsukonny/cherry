<?php

declare(strict_types=1);

use App\Http\Request;

test('Request: path is normalized and split into category and post slugs', function (): void {
    $_SERVER['REQUEST_URI'] = '/morozostoykie/stepnaya-vishnya/?page=2';
    $_SERVER['REQUEST_METHOD'] = 'get';
    $_GET = ['page' => '2'];

    $request = Request::parseRequest();

    assertSame('GET', $request->method);
    assertSame('/morozostoykie/stepnaya-vishnya', $request->path);
    assertSame('morozostoykie', $request->categorySlug);
    assertSame('stepnaya-vishnya', $request->postSlug);
    assertSame('2', $request->query('page'));
});

test('Request: query() ignores arrays and missing keys', function (): void {
    $request = new Request(query: ['sort' => ['views'], 'page' => '3']);

    assertSame(null, $request->query('sort'));
    assertSame(null, $request->query('missing'));
    assertSame('3', $request->query('page'));
});

test('Request: properties are read-only', function (): void {
    $request = new Request();

    assertThrows(Error::class, function () use ($request): void {
        $request->method = 'POST';
    });
});
