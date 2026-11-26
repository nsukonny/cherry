<?php

declare(strict_types=1);

use App\View\Pagination;

test('Pagination: pages are rounded up, offset follows the page', function (): void {
    $p = new Pagination(current: 2, total: 7, perPage: 3, path: '/frost/');

    assertSame(3, $p->pages);
    assertSame(3, $p->offset());
    assertSame(true, $p->hasPrevious());
    assertSame(true, $p->hasNext());
    assertSame([1, 2, 3], $p->numbers());
});

test('Pagination: empty list still has one page', function (): void {
    $p = new Pagination(current: 1, total: 0, perPage: 3, path: '/frost/');

    assertSame(1, $p->pages);
    assertSame(true, $p->exists());
    assertSame(false, $p->hasNext());
});

test('Pagination: page out of range does not exist', function (): void {
    assertSame(false, (new Pagination(3, 4, 3, '/frost/'))->exists());
    assertSame(false, (new Pagination(0, 4, 3, '/frost/'))->exists());
});

test('Pagination: links keep sort, first page has no ?page=', function (): void {
    $p = new Pagination(1, 9, 3, '/frost/', ['sort' => 'views']);

    assertSame('/frost/?sort=views', $p->url(1));
    assertSame('/frost/?sort=views&page=2', $p->url(2));
    assertSame('/frost/', (new Pagination(2, 9, 3, '/frost/'))->url(1));
});
