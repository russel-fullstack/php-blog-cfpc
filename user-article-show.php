<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';

$article_id = $_GET['id'];
$sql = 'SELECT * FROM articles WHERE id = :article_id';
$query = $pdo->prepare($sql);
$query->execute(compact('article_id'));
$article = $query->fetch();

$sql = 'SELECT comments.*, users.pseudo
FROM comments
JOIN users ON comments.user_id = users.id
WHERE article_id= :article_id';

$query = $pdo->prepare($sql);
$query->execute(compact('article_id'));
$commentaires = $query->fetchAll();

$usercount = $pdo->query('SELECT COUNT(*) AS count FROM users')->fetch(PDO::FETCH_ASSOC)['count'];
$commentsCount = $pdo->query('SELECT COUNT(*) AS count FROM comments')->fetch(PDO::FETCH_ASSOC)['count'];
$articlecount = $pdo->query('SELECT COUNT(*) AS count FROM articles')->fetch(PDO::FETCH_ASSOC)['count'];

$latesArticles = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC LIMIT 5')->fetch(PDO::FETCH_ASSOC);

$pageTitle = 'Affichage des articles';

render('blog/user-article-show', [
    'pageTitle' => $pageTitle,
    'article' => $article,
    'article_id' => $article_id,
    'commentaires' => $commentaires,
    'usercount' => $usercount,
    'commentsCount' => $commentsCount,
    'articlecount' => $articlecount,
    'latesArticles' => $latesArticles
]);