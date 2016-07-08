<?php

// check if user is logged in, else redirect to login page
$app->add(function ($request, $response, $next) {
	if (!isset($_SESSION['user'])) {
		$uri = $request->getUri()->getPath();

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

$app->get('/recipe/{path}', '\Recipe:view');

$app->get('/achterkant/recipe/edit[/{id}]', '\Recipe:edit');

$app->post('/achterkant/recipe/save[/{id}]', '\Recipe:save');

