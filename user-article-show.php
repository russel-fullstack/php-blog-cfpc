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

$usercount = $pdo->query('SELECT COUNT(*) AS count FROM users')->fetch(PDO::FETCH_ASSOC)['count'];
$articlecount = $pdo->query('SELECT COUNT(*) AS count FROM articles')->fetch(PDO::FETCH_ASSOC)['count'];

$latesArticles = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC LIMIT 5')->fetch(PDO::FETCH_ASSOC);

$pageTitle = 'Affichage des articles';// Titre de la page d'accueil du blog
ob_start();// créer un tampon de sortie pour stocker le contenu de la page d'accueil du blog

require_once 'resources/views/blog/user-article-show_html.php';

$pageContent = ob_get_clean(); // Récupérer le contenu du tampon de sortie et le stocker dans la variable $pageContent
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php'; //Inclure le layout du blog qui affichera le header, le contenu et le footer