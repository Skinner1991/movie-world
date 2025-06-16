<?php

declare(strict_types=1);

namespace App\Validators;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class ValidateMovie
{
    private Request $request;
    private array $data;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = $request->getParsedBody() ?? [];
    }

    public function validateCreate(): array
    {
        $title = trim($this->data['title'] ?? '');
        $description = trim($this->data['description'] ?? '');

        if (empty($title)) {
            throw new HttpBadRequestException($this->request, "Missing or empty title.");
        }

        if (empty($description)) {
            throw new HttpBadRequestException($this->request, "Missing or empty description.");
        }

        return [
            'title' => $title,
            'description' => $description
        ];
    }

    public function validateVote(): ?string
    {
        $data = $this->request->getParsedBody() ?? [];
        $voteType = $data['vote_type'] ?? null;

        if (!in_array($voteType, ['like', 'hate', null], true)) {
            throw new HttpBadRequestException($this->request, 'Invalid vote type. Use "like", "hate", or null.');
        }

        return $voteType;
    }
}
