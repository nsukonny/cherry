<?php

/**
 * Minimal test runner without dependencies: composer test
 *
 * Each tests/*Test.php file calls test('name', fn () => ...) and uses the assert helpers below.
 * Tests cover code without database or HTTP: routing, request parsing, pagination, plural forms.
 */

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var array<string, callable> $tests */
$tests = [];

function test(string $name, callable $body): void
{
    global $tests;
    $tests[$name] = $body;
}

function assertSame(mixed $expected, mixed $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        throw new AssertionError(sprintf(
            "%sexpected %s, got %s",
            $message === '' ? '' : $message . ': ',
            var_export($expected, true),
            var_export($actual, true),
        ));
    }
}

/**
 * @param class-string<Throwable> $class
 */
function assertThrows(string $class, callable $body): Throwable
{
    try {
        $body();
    } catch (Throwable $e) {
        if ($e instanceof $class) {
            return $e;
        }
        throw new AssertionError('expected ' . $class . ', got ' . $e::class . ': ' . $e->getMessage());
    }

    throw new AssertionError('expected ' . $class . ', nothing was thrown');
}

foreach (glob(__DIR__ . '/*Test.php') as $file) {
    require $file;
}

$failed = 0;
foreach ($tests as $name => $body) {
    try {
        $body();
        echo "  ✓ {$name}\n";
    } catch (Throwable $e) {
        $failed++;
        echo "  ✗ {$name}\n    {$e->getMessage()}\n";
    }
}

printf("\n%d tests, %d failed\n", count($tests), $failed);
exit($failed === 0 ? 0 : 1);
