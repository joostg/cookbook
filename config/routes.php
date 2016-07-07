<?php

// check if user is logged in, else redirect to login page
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

$app->get('/login', function ($request, $response, $args) {
	return $this->view->render($response, 'login/login.tpl');
});

$app->post('/login', function ($request, $response, $args) {
	$email = $request->getParam('user');
	$pass = $request->getParam('pass');

	if (!$pass || !$email) {
		return $response->withHeader('Location', 'login');
	}

	$user = new User($this->db);
	if ($user->authenticate($email, $pass)) {
		$_SESSION['user'] = $email;

		$uri = '/';
		if ($_SESSION['returnUrl']) {
			$uri = $_SESSION['returnUrl'];
		}
		// @TODO: fix return url
		return $response->withHeader('Location', '/recipe');
	}

	return $response->withHeader('Location', 'login');
});

$app->get('/logout', function ($request, $response, $args) {
	session_destroy();

	return $response->withHeader('Location', 'login');
});

$app->get('/recipe', function ($request, $response, $args) {
	$mapper = new Recipe($this->db);
	$data = $mapper->getRecipes();

	return $this->view->render($response, 'recipe/browse.tpl', $data);
});//->setName('profile');


