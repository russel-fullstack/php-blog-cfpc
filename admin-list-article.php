<?php
declare(strict_types=1);
session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== Role::ADMIN->value) 
  {
    header('Location: index.php');
    exit();
}
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = clean_input((string) ($_POST['search'] ?? ''));
}

$query = 'SELECT * FROM articles';

if (!empty($searchTerm)) {
    $query .= ' WHERE title LIKE ? OR introduction LIKE ?';
}
$query .= ' ORDER BY created_at DESC';
$resultats = $pdo->prepare($query);
if(!empty($searchTerm)) {
    $resultats->bindValue(1, '%' . $searchTerm . '%');
    $resultats->bindValue(2, '%' . $searchTerm . '%');
}
$resultats->execute();
$articles = $resultats->fetchAll();

$pageTitle = 'List Articles';
ob_start();
require_once 'resources/views/admin/articles/admin-list-article_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';
