<?php

/**
 * Syntax check (php -l) of every project PHP file: composer lint
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$failed = 0;
$checked = 0;

foreach (['bin', 'config', 'database', 'public', 'src', 'tests'] as $dir) {
    if (!is_dir("{$root}/{$dir}")) {
        continue;
    }

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("{$root}/{$dir}", FilesystemIterator::SKIP_DOTS));

    foreach ($files as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $checked++;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname()) . ' 2>&1', $output, $code);

        if ($code !== 0) {
            $failed++;
            echo implode(PHP_EOL, $output), PHP_EOL;
        }

        $output = [];
    }
}

printf("%d files checked, %d with errors\n", $checked, $failed);
exit($failed === 0 ? 0 : 1);
