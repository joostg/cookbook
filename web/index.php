<?php
namespace cookbook;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
spl_autoload_register(function ($classname) {
	$file = __DIR__ . '/../' . $classname . '.php';

	if (!file_exists($file)) {
		return false;
	}

	require $file;

	return true;

	//require ("../classes/" . $classname . ".php");
});

require '../config/config.php';

$app = new \Slim\App(["settings" => $config]);

session_name('cookbook');
session_set_cookie_params(86400);
session_start();

// load all dependencies
require '../config/dependencies.php';

// add routes
require '../config/routes.php';

$app->run();
