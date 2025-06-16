<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use App\Middleware\CustomAuthMiddleware;
use App\Controllers\MovieController;
use App\Controllers\AuthController;

return function (App $app) {

    $app->post('/api/signup', [AuthController::class, 'signup']);
    $app->post('/api/login', [AuthController::class, 'login']);
    $app->get('/api/movies', [MovieController::class, 'getAll']);

    $app->group('/api', function ($group) {
        $group->post('/movies', [MovieController::class, 'add']);
        $group->post('/movies/{id}/vote', [MovieController::class, 'vote']);
    })->add(new CustomAuthMiddleware($app->getResponseFactory()));
};
