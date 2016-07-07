<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
spl_autoload_register(function ($classname) {
	require ("../classes/" . $classname . ".php");
});

require '../config/config.php';

$app = new \Slim\App(["settings" => $config]);

session_name('cookbook');
session_set_cookie_params(604800);
session_start();

// load all dependencies
require '../config/dependencies.php';

$app->add(function ($request, $response, $next) {
	if (!isset($_SESSION['user'])) {
		$uri = $request->getUri()->getPath();

		if ($uri != '/login') {
			$_SESSION['returnUrl'] = $uri;

			return $response->withHeader('Location', 'login');
		}
	}

	return $next($request, $response);
});

/*
if (!isset($_SESSION['user']) && $_SERVER['REQUEST_URI'] != '/login') {
	header('Location: /login');
	die();
}*/

var_dump($_SESSION);
// add routes
require '../config/routes.php';

$app->run();
