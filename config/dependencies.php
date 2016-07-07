<?php

// create a container for all dependencies
$container = $app->getContainer();

// Register Twig on container
$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig('../tpl', [
		//'cache' => 'path/to/cache'
	]);
	$view->addExtension(new \Slim\Views\TwigExtension(
		$container['router'],
		$container['request']->getUri()
	));

	return $view;
};

// add Monolog logger
$container['logger'] = function($c) {
	$logger = new \Monolog\Logger('my_logger');
	$file_handler = new \Monolog\Handler\StreamHandler("../log/cookbook.log");
	$logger->pushHandler($file_handler);

	return $logger;
};

// connect to DB
$container['db'] = function ($c) {
	$db = $c['settings']['db'];
	$pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
		$db['user'], $db['pass']);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $pdo;
};

$container['flash'] = function () {
	return new \Slim\Flash\Messages();
};