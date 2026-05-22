<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database/database.php';

function countComments(): int
{
    $pdo = getPdo();
    $query = $pdo->query('SELECT COUNT(*) FROM comments');
    return (int) $query->fetchColumn();
}

function findCommentsByArticle(int $article_id): array
{
    $pdo = getPdo();
    $sql = 'SELECT comments.*, users.pseudo
            FROM comments
            JOIN users 
            ON comments.user_id = users.id
            WHERE article_id= :article_id';

    $query = $pdo->prepare($sql);
    $query->execute(compact('article_id'));
    return  $query->fetchAll();
}
function insertComment(string $content, int $user_auth, int $article_id): bool
{
    $pdo = getPdo();
    $query = $pdo->prepare('INSERT INTO comments (content, article_id, user_id, created_at) VALUES (:content, :article_id, :user_auth, NOW())');
    return $query->execute(compact('content', 'article_id', 'user_auth'));
}

function deleteComment(int $comment_id): bool
{
    $pdo = getPdo();
    $query = $pdo->prepare('DELETE FROM comments WHERE id = :comment_id');
    return $query->execute(['comment_id' => $comment_id]);
}
function findComment(int $comment_id): array|false
{
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT user_id FROM comments WHERE id = :comment_id');
    $query->execute(['comment_id' => $comment_id]);
    $comment = $query->fetch();
    return  $comment;
}
function findCommentsByUser(int $userId)
{
    $pdo = getPdo();
    $commentsQuery = $pdo->prepare('
        SELECT c.id, c.content, c.created_at, a.id AS article_id, a.title AS article_title, a.slug AS article_slug
        FROM comments c
        LEFT JOIN articles a ON c.article_id = a.id
        WHERE c.user_id = :user_id
    ');
    $commentsQuery->execute(['user_id' => $userId]);
    return $commentsQuery->fetchAll(PDO::FETCH_ASSOC);
}

