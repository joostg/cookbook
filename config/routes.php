<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
	// Sample log message
//	$this->logger->info("Slim-Skeleton '/' route");

	// Render index view
	return $this->renderer->render($response, 'index.phtml', $args);
});*/


$app->get('/login', function ($request, $response, $args) {
	return $this->view->render($response, 'login/login.tpl');
});

$app->post('/login', function ($request, $response, $args) {
	$email = $request->getParam('user');
	$pass = $request->getParam('pass');

	if (!$pass || $email) {
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


