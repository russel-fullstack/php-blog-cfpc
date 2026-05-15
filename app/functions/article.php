<?php

declare(strict_types=1);

require_once __DIR__ . '/database/database.php';

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