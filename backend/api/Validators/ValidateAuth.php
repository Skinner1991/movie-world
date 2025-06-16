<?php

declare(strict_types=1);

namespace App\Validators;

use Slim\Exception\HttpBadRequestException;
use Psr\Http\Message\ServerRequestInterface as Request;

class ValidateAuth
{
    private Request $request;
    private array $data;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = $request->getParsedBody() ?? [];
    }

    public function validateSignupInput(): array
    {
        if (empty($this->data['name'])) {
            throw new HttpBadRequestException($this->request, "Name is required.");
        }

        if (empty($this->data['email'])) {
            throw new HttpBadRequestException($this->request, "Email is required.");
        }

        if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new HttpBadRequestException($this->request, "Email is not valid.");
        }

        if (empty($this->data['password'])) {
            throw new HttpBadRequestException($this->request, "Password is required.");
        }

        if (strlen($this->data['password']) < 6) {
            throw new HttpBadRequestException($this->request, "Password must be at least 6 characters.");
        }

        return [
            'name' => trim($this->data['name']),
            'email' => trim($this->data['email']),
            'password' => $this->data['password']
        ];
    }

    public function validateLoginInput(): array
    {
        if (empty($this->data['email'])) {
            throw new HttpBadRequestException($this->request, "Email is required.");
        }

        if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new HttpBadRequestException($this->request, "Email is not valid.");
        }

        if (empty($this->data['password'])) {
            throw new HttpBadRequestException($this->request, "Password is required.");
        }

        return [
            'email' => trim($this->data['email']),
            'password' => $this->data['password']
        ];
    }
}
