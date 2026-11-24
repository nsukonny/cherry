<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;

class Router {

	private array $routes = [];

	public function __construct() {
	}

	/**
	 * Add a route to the router.
	 *
	 * @param string $method
	 * @param string $path
	 * @param string $controller
	 *
	 * @return void
	 */
	#[NoReturn]
	public function add( string $method, string $path, string $controller ): void {
		$this->routes[] = compact( 'method', 'path', 'controller' );
	}

	/**
	 * Run the router and match the current request to a route.
	 *
	 * @return void
	 */
	#[NoReturn]
	public function run(): void {
		echo '<pre>---prd-' . print_r( $this->routes, true ) . '</pre>';
		die();
	}


}