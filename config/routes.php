<?php

// check if user is logged in, else redirect to login page
$app->add(function ($request, $response, $next) {
	$uri = $uri = $request->getUri()->getPath();

	if (!isset($_SESSION['user']) && strpos($uri, '/achterkant') === 0) {
		if ($uri != '/login') {
			$_SESSION['returnUrl'] = $uri;

			return $response->withHeader('Location', '/login');
		}
	}

	return $next($request, $response);
});

$app->get('/', \cookbook\frontend\classes\Home::class . ':view');
$app->get('/recept/{path}',  \cookbook\frontend\classes\Recipe::class . ':view');


$app->get('/achterkant', \cookbook\backend\classes\Dashboard::class . ':browse');

/* ======================
 * Backend Authentication
 * ====================== */
$app->get('/login', \cookbook\backend\classes\User::class . ':login');
$app->post('/login', \cookbook\backend\classes\User::class . ':authenticate');
$app->post('/restore-cookie', \cookbook\backend\classes\User::class . ':restoreCookie');
$app->get('/logout', \cookbook\backend\classes\User::class . ':logout');


/* ===============
 * Backend Recipes
 * =============== */
$app->get('/recepten', \cookbook\backend\classes\Recipe::class . ':list');
$app->get('/recepten/wijzigen[/{id}]', \cookbook\backend\classes\Recipe::class . ':edit');
$app->post('/recepten/opslaan[/{id}]', \cookbook\backend\classes\Recipe::class . ':save');

/* ===================
 * Backend Ingredients
 * =================== */
$app->get('/ingredienten', \cookbook\backend\classes\Ingredient::class . ':list');
$app->get('/ingredienten/wijzigen[/{id}]', \cookbook\backend\classes\Ingredient::class . ':edit');
$app->post('/ingredienten/opslaan[/{id}]', \cookbook\backend\classes\Ingredient::class . ':save');

/* ==================
 * Backend Quantities
 * ================== */
$app->get('/hoeveelheden', \cookbook\backend\classes\Quantity::class .  ':list');
$app->get('/hoeveelheden/wijzigen[/{id}]', \cookbook\backend\classes\Quantity::class .  ':edit');
$app->post('/hoeveelheden/opslaan[/{id}]', \cookbook\backend\classes\Quantity::class . ':save');