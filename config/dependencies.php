<?php

/**
 * autoloader that sets proper namespace and converts Windows slashes to regular slashes
 */
spl_autoload_register(function ($classname) {
    $file = __DIR__ . '/../' . str_replace('\\','/',str_replace('cookbook\\','',$classname)) . '.php';

    if (!file_exists($file)) {
        return false;
    }

    require $file;

    return true;
});

// create a container for all dependencies
$container = $app->getContainer();

// Register Twig on container
$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig('../', [
		'autoescape' => false
	]);
	$view->addExtension(new \Slim\Views\TwigExtension(
		$container['router'],
		$container['request']->getUri()
	));
	$view->addExtension(new Cocur\Slugify\Bridge\Twig\SlugifyExtension(
		Cocur\Slugify\Slugify::create()
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

// Register provider
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Add slugify for pathnames
$container['slugify'] = function ($c) {
	$slugify = new Cocur\Slugify\Slugify;
	return $slugify;
};

$container['capsule'] = function ($container) {
    $capsule = new Illuminate\Database\Capsule\Manager;

    $db = $container['settings']['db'];

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => $db['host'],
        'database'  => $db['dbname'],
        'username'  => $db['user'],
        'password'  => $db['pass'],
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    $capsule->bootEloquent();

    unset($container['settings']['db']);

    return $capsule;
};

// prepare clean print-function
function printr($data = '')
{
	echo '<pre class="printr">';
	print_r($data);
	echo '</pre>' . "\n";
}