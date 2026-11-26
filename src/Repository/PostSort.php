<?php

/**
 * Allowed sort orders for post lists
 */

declare(strict_types=1);

namespace App\Repository;

enum PostSort: string
{
    case Date = 'date';
    case Views = 'views';

    /**
     * Text for the sort switcher on the category page.
     */
    public function label(): string
    {
        return match ($this) {
            self::Date => 'Сначала новые',
            self::Views => 'Популярные',
        };
    }
}
