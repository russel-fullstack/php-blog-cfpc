<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';

$article_id = $_GET['id'];
$article = Article::find((int)$article_id);

$commentaires = Comment::findByArticle((int)$article_id);

$usercount = User::count();
$commentsCount = Comment::count();
$articlecount = Article::count();

$latesArticles = Article::findAll(5, 0);

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
