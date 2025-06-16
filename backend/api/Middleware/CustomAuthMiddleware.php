<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseFactoryInterface;

use Psr\Http\Message\ResponseInterface;

use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use App\Helpers\ResponseHelper;

class CustomAuthMiddleware
{
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandler $handler): ResponseInterface
    {
        $authorizationHeader  = $request->getHeaderLine('Authorization');
        $token = $this->extractToken($authorizationHeader);

        if (!$token)
            return $this->unauthorized('Token not provided');

        try {
            $secret = $_ENV['JWT_SECRET'] ?? 'default_fallback';
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            $request = $request->withAttribute('user', $decoded);

            return $handler->handle($request);
        } catch (\Exception $e) {
            return ResponseHelper::json(
                $this->responseFactory->createResponse(),
                ['message' => 'Unauthorized', 'error' => $e->getMessage()],
                401
            );
        }
    }

    private function unauthorized(string $message): ResponseInterface
    {
        return ResponseHelper::json(
            $this->responseFactory->createResponse(),
            ['message' => $message],
            401
        );
    }

    private function extractToken(string $authorizationHeader): string | null
    {
        if (strpos($authorizationHeader, 'Bearer ') === 0) {
            return substr($authorizationHeader, 7);
        }
        return $authorizationHeader;
    }
}
