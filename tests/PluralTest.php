<?php

declare(strict_types=1);

use App\View\SmartyView;

test('plural: Russian forms for 1, 2-4, 5+, 11-14 and 21', function (): void {
    $cases = [
        0 => 'статей', 1 => 'статья', 2 => 'статьи', 4 => 'статьи', 5 => 'статей',
        11 => 'статей', 14 => 'статей', 21 => 'статья', 22 => 'статьи', 111 => 'статей',
    ];

    foreach ($cases as $count => $expected) {
        assertSame($expected, SmartyView::plural($count, 'статья', 'статьи', 'статей'), "count {$count}");
    }
});
