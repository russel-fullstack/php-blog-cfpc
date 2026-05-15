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
$sql = "SELECT * FROM users";
$query = $pdo->prepare($sql);
$query->execute();
$users = $query->fetchAll();


$pageTitle = "Liste d'utilisateurs";

render('admin/users/users-list', [
  'pageTitle' => $pageTitle,
  'users' => $users
], 'admin-layout');
