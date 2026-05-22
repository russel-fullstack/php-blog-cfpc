<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';

// Vérification des autorisations admin

checkAdmin();

// Récupérer les utilisateurs AVEC leur nombre de commentaires

$users = findUsersWithCommentCount();

// Récupérer les commentaires + infos de l'article pour chaque utilisateur
foreach ($users as &$user) {
    
    $user['comments'] =findCommentsByUser((int)$user['id']);
}

$pageTitle = 'Récupérer tous les utilisateurs';

render('admin/comments-list', [
    'pageTitle' => $pageTitle,
    'users' => $users
], 'admin-layout');