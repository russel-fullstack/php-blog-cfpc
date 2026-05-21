<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database/database.php';

function countUsers(): int
{
    $pdo = getPdo();
    $query = $pdo->query('SELECT COUNT(*) FROM users');
    return (int) $query->fetchColumn();
}

function findUserById(int $id): array|false
{
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $query->execute([':id' => $id]);
    return $query->fetch();
}
function findUserByUsernameExcept(string $username, int $userId): array|false
{
    $pdo = getPdo();
    $query = 'SELECT * FROM users WHERE pseudo = :username AND id != :userId';
    $req = $pdo->prepare($query);
    $req->execute([
        ':username' => $username,
        ':userId' => $userId
    ]);
    return $req->fetch();
}
function findUserByEmailExcept(string $email, int $userId): array|false
{
    $pdo = getPdo();
    $query = 'SELECT * FROM users WHERE email = :email AND id != :userId';
    $req = $pdo->prepare($query);
    $req->execute([
        ':email' => $email,
        ':userId' => $userId
    ]);
    return $req->fetch();
}
