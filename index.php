<?php
declare(strict_types=1);
session_start();
require_once './database/database.php';
require_once 'flash.php';
require_once 'app/helpers.php';

$totalQuery = $pdo-> prepare('SELECT COUNT(*) FROM articles');
$totalQuery->execute();
$totalArticles = (int) $totalQuery->fetchColumn();
$itemsPerPage = 12;
$currentPage =(int)($_GET['page'] ?? 1);
$totalPages = (int) ceil($totalArticles / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;   

$sql = "SELECT * FROM articles ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();

$pageTitle = 'Notre blog d\'accueil';

render('blog/index', [
    'pageTitle' => $pageTitle,
    'articles' => $articles,
    'currentPage' => $currentPage,
    'totalPages' => $totalPages
])

?>