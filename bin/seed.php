<?php

/**
 * Заливает категории и статьи в базу. Можно запускать повторно.
 *
 * docker compose exec app php bin/seed.php
 */

declare(strict_types=1);

use App\Database\Connection;
use App\Database\Seeder;

$rootPath = dirname(__DIR__);
$config = require $rootPath . '/config/bootstrap.php';

try {
    $seeder = new Seeder(Connection::create($config['database']));
    $result = $seeder->run(require $rootPath . '/database/seed-data.php');
} catch (Throwable $e) {
    fwrite(STDERR, 'Seeding failed: ' . $e->getMessage() . PHP_EOL);
    if ($e->getPrevious() !== null) {
        fwrite(STDERR, 'Cause: ' . $e->getPrevious()->getMessage() . PHP_EOL);
    }
    exit(1);
}

printf(
    "Seeded: %d categories, %d posts, %d post-category links.\n",
    $result['categories'],
    $result['posts'],
    $result['links'],
);
