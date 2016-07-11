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

$app->get('/', '\Home:view');

$app->get('/login', '\User:login');

$app->post('/login', '\User:authenticate');

$app->get('/logout', '\User:logout');

$app->get('/recept/{path}', '\Recipe:view');

$app->get('/achterkant', '\Dashboard:view');

$app->get('/achterkant/recepten', '\Recipe:admin_list');

$app->get('/achterkant/recepten/wijzigen[/{id}]', '\Recipe:admin_edit');

$app->post('/achterkant/recepten/opslaan[/{id}]', '\Recipe:admin_save');

$app->get('/achterkant/ingredienten', '\Ingredient:admin_list');

$app->get('/achterkant/ingredienten/wijzigen[/{id}]', '\Ingredient:admin_edit');

$app->post('/achterkant/ingredienten/opslaan[/{id}]', '\Ingredient:admin_save');