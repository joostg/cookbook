<?php

// check if user is logged in, else redirect to login page
$app->add(function ($request, $response, $next) {
	$uri = $uri = $request->getUri()->getPath();

	if (!isset($_SESSION['user']) && strpos($uri, '/achterkant') === 0) {
		if ($uri != '/achterkant/login') {
			$_SESSION['returnUrl'] = $uri;

			return $response->withHeader('Location', '/achterkant/login');
		}
	}

	return $next($request, $response);
});

$app->get('/', \cookbook\frontend\classes\Home::class . ':view');
$app->get('/recept/{path}',  \cookbook\frontend\classes\Recipe::class . ':view');


$app->get('/achterkant', \cookbook\backend\classes\Dashboard::class . ':browse');
$app->get('/achterkant/', \cookbook\backend\classes\Dashboard::class . ':browse');

/* ======================
 * Backend Authentication
 * ====================== */
$app->get('/achterkant/login', \cookbook\backend\classes\User::class . ':login');
$app->post('/achterkant/login', \cookbook\backend\classes\User::class . ':authenticate');
$app->post('/achterkant/restore-cookie', \cookbook\backend\classes\User::class . ':restoreCookie');
$app->get('/achterkant/logout', \cookbook\backend\classes\User::class . ':logout');


/* ===============
 * Backend Recipes
 * =============== */
$app->get('/achterkant/recepten', \cookbook\backend\classes\Recipe::class . ':list');
$app->get('/achterkant/recepten/wijzigen[/{id}]', \cookbook\backend\classes\Recipe::class . ':edit');
$app->get('/achterkant/recepten/verwijderen[/{id}]', \cookbook\backend\classes\Recipe::class . ':delete');
$app->post('/achterkant/recepten/opslaan[/{id}]', \cookbook\backend\classes\Recipe::class . ':save');

/* ===================
 * Backend Ingredients
 * =================== */
$app->get('/achterkant/ingredienten', \cookbook\backend\classes\Ingredient::class . ':list');
$app->get('/achterkant/ingredienten/wijzigen[/{id}]', \cookbook\backend\classes\Ingredient::class . ':edit');
$app->get('/achterkant/ingredienten/verwijderen[/{id}]', \cookbook\backend\classes\Ingredient::class . ':delete');
$app->post('/achterkant/ingredienten/opslaan[/{id}]', \cookbook\backend\classes\Ingredient::class . ':save');

/* ==================
 * Backend Quantities
 * ================== */
$app->get('/achterkant/hoeveelheden', \cookbook\backend\classes\Quantity::class .  ':list');
$app->get('/achterkant/hoeveelheden/wijzigen[/{id}]', \cookbook\backend\classes\Quantity::class .  ':edit');
$app->get('/achterkant/hoeveelheden/verwijderen[/{id}]', \cookbook\backend\classes\Quantity::class . ':delete');
$app->post('/achterkant/hoeveelheden/opslaan[/{id}]', \cookbook\backend\classes\Quantity::class . ':save');

$app->get('/achterkant/afbeeldingen', \cookbook\backend\classes\ImageViewer::class .  ':browse');
$app->post('/achterkant/afbeeldingen/upload', \cookbook\backend\classes\ImageViewer::class .  ':upload');