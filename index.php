<?php
declare(strict_types=1);
session_start();
require_once './database/database.php';
require_once 'flash.php';
require_once 'app/helpers.php';

$totalArticles = countArticles();
$itemsPerPage = 12;
$currentPage =(int)($_GET['page'] ?? 1);
$totalPages = (int) ceil($totalArticles / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;   

$articles = findAllArticles($itemsPerPage, $offset);


$pageTitle = 'Notre blog d\'accueil';

render('blog/index', [
    'pageTitle' => $pageTitle,
    'articles' => $articles,
    'currentPage' => $currentPage,
    'totalPages' => $totalPages
])

?>