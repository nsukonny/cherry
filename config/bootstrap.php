<?php

declare(strict_types=1);

use App\Config;

$rootPath = dirname(__DIR__);

require_once $rootPath . '/vendor/autoload.php';

date_default_timezone_set('UTC');

return Config::load($rootPath);
