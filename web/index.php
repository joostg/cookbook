<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
spl_autoload_register(function ($classname) {
	require ("../classes/" . $classname . ".php");
});

require '../config/config.php';

$app = new \Slim\App(["settings" => $config]);

// load all dependencies
require '../config/dependencies.php';

// add routes
require '../config/routes.php';

$app->run();
