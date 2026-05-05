<?php
declare(strict_types=1);
session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== Role::ADMIN->value) {
    header('Location: index.php');
    exit();
}

$query = "SELECT * FROM articles";
$stmt = $pdo->prepare($query);
$stmt->execute();
$articles = $stmt->fetchAll();

$pageTitle = "Liste des articles";
ob_start();
require_once 'resources/views/admin/articles/admin-list-article_html.php';

$pageContent = ob_get_clean(); // Récupérer le contenu du tampon de sortie et le stocker dans la variable $pageContent
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php'; //Inclure le layout du blog qui affichera le header, le contenu et le footer
