<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Models\UserModel;
use App\Validators\ValidateAuth;
use App\Helpers\ResponseHelper;
use App\Helpers\AuthHelper;

class AuthController
{
    public function signup(Request $request, Response $response): Response
    {
        $validator = new ValidateAuth($request);
        $input = $validator->validateSignupInput();

        $model = new UserModel();

        if ($model->emailExists($input['email'])) {
            throw new HttpBadRequestException($request, 'Email already in use.');
        }

        $userId = $model->createUser($input['name'], $input['email'], $input['password']);
        $token = AuthHelper::makeJWTToken($userId, $input['name']);

        return ResponseHelper::json($response, ['token' => $token, 'userId' => $userId, 'username' => $input['name']]);
    }

    public function login(Request $request, Response $response): Response
    {
        $validator = new ValidateAuth($request);
        $input = $validator->validateLoginInput();

        $model = new UserModel();
        $user = $model->verifyUser($input['email'], $input['password']);

        if (!$user) {
            throw new HttpBadRequestException($request, 'Invalid credentials.');
        }

        $token = AuthHelper::makeJWTToken($user['id'], $user['name']);
        return ResponseHelper::json($response, ['token' => $token, 'userId' => $user['id'], 'username' => $user['name']]);
    }
}
