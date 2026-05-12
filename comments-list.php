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
$sql = "SELECT * FROM comments";
$query = $pdo->prepare($sql);
$query->execute();
$comments = $query->fetchAll();

$pageTitle = "Liste de commentaires";
ob_start();
require_once 'resources/views/admin/comments-list_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';
