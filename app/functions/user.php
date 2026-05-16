<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database/database.php';

function countUsers(): int
{
    $pdo = getPdo();
    $query = $pdo->query('SELECT COUNT(*) FROM users');
    return (int) $query->fetchColumn();
}

