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
	$user = $request->getParam('user');
	$pass = $request->getParam('pass');

	if (!$pass) {
		return $response->withHeader('Location', 'login');
	}

	$_SESSION['user'] = $user;
	$_SESSION['pass'] = $pass;
	return $response->withHeader('Location', 'recipe');
});

$app->get('/logout', function ($request, $response, $args) {
	session_destroy();

	return $response->withHeader('Location', 'login');
});

$app->get('/recipe', function ($request, $response, $args) {
	$mapper = new Recipe($this->db);
	$data = $mapper->getRecipes();
	//$response = $this->view->render($response, "tickets.phtml", ["tickets" => $tickets, "router" => $this->router]);

	return $this->view->render($response, 'recipe/browse.tpl', $data);
});//->setName('profile');


