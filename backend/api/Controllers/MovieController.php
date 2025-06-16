<?php

namespace App\Controllers;

use App\Database;

use App\Helpers\AuthHelper;
use App\Helpers\MovieHelper;
use App\Helpers\ResponseHelper;
use App\Validators\ValidateMovie;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\MovieModel;

class MovieController
{
    public function getAll(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $orderby = $params['orderby'] ?? null;
        $userIdFilter = isset($params['user_id']) ? (int) $params['user_id'] : null;

        $model = new MovieModel();
        $movies = $model->getAll($orderby, $userIdFilter);

        $return = [
            'result' => $movies,
            'count' => count($movies),
            'message' => empty($movies) ? 'No movies found' : 'Movies found'
        ];

        return ResponseHelper::json($response, $return);
    }

    public function add(Request $request, Response $response): Response
    {
        ['title' => $title, 'description' => $description] = (new ValidateMovie($request))->validateCreate();
        $userId = AuthHelper::getUserId($request);

        $model = new MovieModel();
        $model->insertMovie($title, $description, $userId);

        return ResponseHelper::json($response, ['message' => 'Movie added successfully'], 201);
    }

    public function vote(Request $request, Response $response, array $args): Response
    {
        $movieId = (int) $args['id'];
        $voteType = (new ValidateMovie($request))->validateVote();
        $userId = AuthHelper::getUserId($request);

        $model = new MovieModel();
        MovieHelper::ensureNotOwner(Database::connect(), $movieId, $userId); // optional: refactor later

        $existingVote = $model->getVote($userId, $movieId);

        if ($voteType === null && $existingVote) {
            $model->deleteVote((int) $existingVote['id']);
            return $this->buildVoteResponse($response, $model, $movieId, null, 'Vote removed');
        }

        if ($existingVote) {
            if ($existingVote['vote_type'] === $voteType) {
                // return $this->buildVoteResponse($response, $model, $movieId, $voteType, 'Same vote already exists');
                $model->deleteVote((int) $existingVote['id']);
                return $this->buildVoteResponse($response, $model, $movieId, null, 'Vote removed');
            }

            $model->updateVote((int) $existingVote['id'], $voteType);
            return $this->buildVoteResponse($response, $model, $movieId, $voteType, 'Vote updated');
        }

        $model->createVote($userId, $movieId, $voteType);
        return $this->buildVoteResponse($response, $model, $movieId, $voteType, 'Vote recorded');
    }

    private function buildVoteResponse(Response $response, MovieModel $model, int $movieId, ?string $currentVote, string $message): Response
    {
        $counts = $model->getVoteCounts($movieId);

        $payload = [
            'likes' => $counts['likes'],
            'hates' => $counts['hates'],
            'current_vote' => $currentVote,
            'message' => $message,
        ];

        return ResponseHelper::json($response, $payload);
    }
}
