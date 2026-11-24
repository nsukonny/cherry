<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;

class Router {

	const VALID_METHODS = [ 'GET', 'POST' ];

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
		if ( ! isset( $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] )
		     || ! in_array( $_SERVER['REQUEST_METHOD'], self::VALID_METHODS, true ) ) {
			http_response_code( 400 );
			echo 'Bad Request';
			exit;
		}

		$current_method = $_SERVER['REQUEST_METHOD'];
		$current_path   = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	}


}