<?php

declare(strict_types=1);

namespace App\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Firebase\JWT\JWT;

class AuthHelper
{
    public static function getUserId(Request $request): ?int
    {
        $user = $request->getAttribute('user');
        $userId = $user->userId ?? null;

        if (empty($userId))
            throw new HttpBadRequestException($request, "Unauthorized – user not found");

        return $userId;
    }

    public static function makeJWTToken($userId, $userName)
    {
        $payload = [
            'userId' => (int)$userId,
            'name' => $userName,
            'iat' => time(),
            'exp' => time() + 3600
        ];

        $jwt_secret = $_ENV['JWT_SECRET'] ?? 'jwt_secret_key';
        $token = JWT::encode($payload, $jwt_secret, 'HS256');

        return $token;
    }
}
