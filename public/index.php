<?php

use App\View\SmartyView;


ini_set( 'display_errors', '1' );
error_reporting( E_ALL );

$config = require dirname( __DIR__ ) . '/config/bootstrap.php';
$view   = new SmartyView( dirname( __DIR__ ), $config['name'] );

require 'routes.php';