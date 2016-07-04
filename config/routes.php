<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
	// Sample log message
//	$this->logger->info("Slim-Skeleton '/' route");

	// Render index view
	return $this->renderer->render($response, 'index.phtml', $args);
});*/

$app->get('/recipe', function ($request, $response, $args) {
	$mapper = new Recipe($this->db);
	$data = $mapper->getRecipes();
	//$response = $this->view->render($response, "tickets.phtml", ["tickets" => $tickets, "router" => $this->router]);

	return $this->view->render($response, 'recipe/browse.tpl', $data);
});//->setName('profile');

