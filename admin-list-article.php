<?php

declare(strict_types=1);
session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== Role::ADMIN->value) {
    redirect('index.php');
}
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = clean_input((string) ($_POST['search'] ?? ''));
}
$articles = findAllArticles(null, null, $searchTerm);

$flash = flash_get();

$pageTitle = 'List Articles';
render('admin/articles/admin-list-article', [
    'pageTitle' => $pageTitle,
    'articles' => $articles,
    'flash' => $flash,
    'searchTerm' => $searchTerm

], 'admin-layout');
