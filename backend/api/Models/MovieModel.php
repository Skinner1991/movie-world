<?php

namespace App\Models;

use PDO;
use App\Database;

class MovieModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getAll(?string $orderby, ?int $userId): array
    {
        $allowedSorts = [
            'like' => 'likes',
            'hate' => 'hates',
            'published' => 'movies.published_at'
        ];

        $orderClause = '';
        if ($orderby && array_key_exists($orderby, $allowedSorts)) {
            $orderClause = 'ORDER BY ' . $allowedSorts[$orderby] . ' DESC';
        }

        $whereClause = '';
        $bindings = [];
        if ($userId) {
            $whereClause = 'WHERE movies.user_id = :user_id';
            $bindings['user_id'] = $userId;
        }

        $sql = "
            SELECT 
                movies.id,
                movies.title,
                movies.description,
                movies.published_at,
                users.name AS user_name,
                users.id AS user_id,
                COUNT(CASE WHEN votes.vote_type = 'like' THEN 1 END) AS likes,
                COUNT(CASE WHEN votes.vote_type = 'hate' THEN 1 END) AS hates
            FROM movies
            JOIN users ON movies.user_id = users.id
            LEFT JOIN votes ON movies.id = votes.movie_id
            $whereClause
            GROUP BY movies.id, users.name, users.id
            $orderClause
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertMovie(string $title, string $description, int $userId): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO movies (title, description, published_at, user_id) VALUES (:title, :description, NOW(), :user_id)");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'user_id' => $userId
        ]);
    }

    public function getVote(int $userId, int $movieId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM votes WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$userId, $movieId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function deleteVote(int $voteId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM votes WHERE id = ?");
        $stmt->execute([$voteId]);
    }

    public function updateVote(int $voteId, string $voteType): void
    {
        $stmt = $this->pdo->prepare("UPDATE votes SET vote_type = ?, created_at = NOW() WHERE id = ?");
        $stmt->execute([$voteType, $voteId]);
    }

    public function createVote(int $userId, int $movieId, string $voteType): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO votes (user_id, movie_id, vote_type) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $movieId, $voteType]);
    }

    public function getVoteCounts(int $movieId): array
    {
        $likes = (int) $this->pdo->query("SELECT COUNT(*) FROM votes WHERE movie_id = $movieId AND vote_type = 'like'")->fetchColumn();
        $hates = (int) $this->pdo->query("SELECT COUNT(*) FROM votes WHERE movie_id = $movieId AND vote_type = 'hate'")->fetchColumn();

        return [
            'likes' => $likes,
            'hates' => $hates
        ];
    }
}
