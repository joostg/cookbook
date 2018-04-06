<?php
namespace cookbook;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

require '../config/config.php';

$app = new \Slim\App(["settings" => $config]);

session_name('onsreceptenboek');
session_set_cookie_params(86400);
session_start();

// load all dependencies
require '../config/dependencies.php';

// add routes
require '../config/routes.php';

$app->run();
