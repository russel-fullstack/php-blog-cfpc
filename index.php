<?php
declare(strict_types=1);
session_start();
require_once './database/database.php';
require_once 'flash.php';

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

$pageTitle = 'Notre blog d\'accueil';// Titre de la page d'accueil du blog
ob_start();// créer un tampon de sortie pour stocker le contenu de la page d'accueil du blog

require_once 'resources/views/blog/index_html.php';

$pageContent = ob_get_clean(); // Récupérer le contenu du tampon de sortie et le stocker dans la variable $pageContent
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php'; //Inclure le layout du blog qui affichera le header, le contenu et le footer


?>