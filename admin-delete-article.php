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

$query = $pdo->prepare('DELETE FROM articles WHERE id = :id');
$query->execute([':id' => $_GET['id']]);
flash_set('success', 'Article supprimé avec succès !');

redirect('admin-list-article.php');