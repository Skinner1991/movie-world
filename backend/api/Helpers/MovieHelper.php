<?php

declare(strict_types=1);

namespace App\Helpers;

use PDO;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;

class MovieHelper
{


    public static function ensureNotOwner(PDO $pdo, int $movieId, int $userId): void
    {
        $stmt = $pdo->prepare("SELECT user_id FROM movies WHERE id = ?");
        $stmt->execute([$movieId]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$movie) {
            throw new HttpNotFoundException(null, 'Movie not found');
        }

        if ((int)$movie['user_id'] === $userId) {
            throw new HttpNotFoundException(null, 'You cannot vote on your own movie');
        }
    }
}
