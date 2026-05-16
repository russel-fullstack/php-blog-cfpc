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
