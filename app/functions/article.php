<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database/database.php';

/**
 * Récupère tous les articles de la base de données
 *
 * Un tableau d'articles
 */


function countArticles(): int
{
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT COUNT(*) AS total FROM articles');
    $query->execute();
    $result = $query->fetch();

    return (int)$result['total'];
}

function findAllArticles( ?int $limit= null, ?int $offset = null, string $searchTerm = ''): array
{
    $sql = 'SELECT 
       articles.*, 
       (SELECT COUNT(*) FROM comments WHERE comments.article_id = articles.id) AS comment_count
        FROM articles 
        ORDER BY created_at DESC 
        LIMIT :limit OFFSET :offset';
    $pdo = getPdo();

    if (!empty($searchTerm)) {
        $sql .= ' WHERE title LIKE ? OR introduction LIKE ?';
    }
    $resultats = $pdo->prepare($sql);
    if (!empty($searchTerm)) {
        $resultats->bindValue(1, '%' . $searchTerm . '%');
        $resultats->bindValue(2, '%' . $searchTerm . '%');
    }
    if ($limit !== null && $offset !== null) {
        $resultats->bindValue(':limit', $limit, PDO::PARAM_INT);
        $resultats->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $resultats->execute();
    return  $resultats->fetchAll();
}
