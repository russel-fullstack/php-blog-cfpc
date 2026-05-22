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

function  insertUser(string $pseudo, string $email, string $password): array|false
{
    $pdo = getPdo();
    $stmt = $pdo->prepare("INSERT INTO users(pseudo, email, password) VALUES(:pseudo, :email, :password)");
    $stmt->execute([':pseudo' => $pseudo, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);
    return $stmt->fetch();
}

function  updateUser(int $userId, string $username, string $email, string $password): array|false
{
    $pdo = getPdo();
    $passwordhash = password_hash($password, PASSWORD_BCRYPT);
    $query = 'UPDATE users SET pseudo = :username, email = :email , password = :password  WHERE id = :userId';
    $params = [
        'username' => $username,
        'email' => $email,
        'userId' => $userId,
        'password' => $passwordhash
    ];
    $req = $pdo->prepare($query);
    $req->execute($params);
    return $req->fetch();
}
function findUsersWithCommentCount()
{
    $pdo = getPdo();
    $usersQuery = $pdo->query('
    SELECT u.id, u.pseudo, COUNT(c.id) AS comment_count
    FROM users u
    LEFT JOIN comments c ON u.id = c.user_id
    GROUP BY u.id
');
    return $usersQuery->fetchAll(PDO::FETCH_ASSOC);
}

function findUserByEmail(string $email) : array|false
{
    $pdo = getPdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    return  $stmt->fetch();
}
function findUserByUsername(string $username) : array|false
{
    $pdo = getPdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
    $stmt->execute([':pseudo' => $username]);
    return  $stmt->fetch();
}
