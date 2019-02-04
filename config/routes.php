<?php

// check if user is logged in, else redirect to login page
$app->add(function ($request, $response, $next) {
    $uri = $request->getUri()->getPath();

    if (strpos($uri, '/achterkant') === 0) {
        if (!isset($_SESSION['user'])) {
            $user = new \cookbook\backend\classes\User($this);

            // attempt to restore login from cookie, else redirect to login page
            if ($user->restoreCookie() !== true) {
                if ($uri != '/achterkant/login') {
                    $_SESSION['returnUrl'] = $uri;

                    return $response->withHeader('Location', '/achterkant/login');
                }
            }
        }
    }

	return $next($request, $response);
});

$app->get('/', \cookbook\frontend\classes\Home::class . ':view');
$app->get('/recepten',  \cookbook\frontend\classes\Recipe::class . ':list');
$app->get('/recepten/{path}',  \cookbook\frontend\classes\Recipe::class . ':view');

$app->group('/achterkant', function () {
    $class = \cookbook\backend\classes\Dashboard::class;

    $this->get('',                  $class . ':browse');
    $this->get('/',                 $class . ':browse');

    $class = \cookbook\backend\classes\User::class;
    $this->get('/login',            $class . ':login');
    $this->post('/login',           $class . ':authenticate');
    $this->get('/restore-cookie',  $class . ':restoreCookie');
    $this->get('/logout',           $class . ':logout');

    $this->group('/recepten', function () {
        $class = \cookbook\backend\classes\Recipe::class;

        $this->get('',                      $class . ':list');
        $this->get('/wijzigen[/{id}]',      $class . ':edit');
        $this->get('/verwijderen[/{id}]',   $class . ':delete');
        $this->post('/opslaan[/{id}]',      $class . ':save');
    });

    $this->group('/ingredienten', function () {
        $class = \cookbook\backend\classes\Ingredient::class;

        $this->get('',                      $class . ':list');
        $this->get('/wijzigen[/{id}]',      $class . ':edit');
        $this->get('/verwijderen[/{id}]',   $class . ':delete');
        $this->post('/opslaan[/{id}]',      $class . ':save');
    });

    $this->group('/hoeveelheden', function () {
        $class = \cookbook\backend\classes\Quantity::class;

        $this->get('',                      $class . ':list');
        $this->get('/wijzigen[/{id}]',      $class . ':edit');
        $this->get('/verwijderen[/{id}]',   $class . ':delete');
        $this->post('/opslaan[/{id}]',      $class . ':save');
    });

    $this->group('/afbeeldingen', function () {
        $class = \cookbook\backend\classes\ImageViewer::class;

        $this->get('',                      $class . ':browse');
        $this->get('/verwijderen[/{id}]',   $class . ':delete');
        $this->post('/upload',              $class . ':upload');
    });
});
