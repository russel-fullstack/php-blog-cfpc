<?php

declare(strict_types=1);


session_start();
require_once 'database/database.php';
require_once 'app/enums/role.php';

// Vérification de l'authentification
if (! isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

$errors = [];
$success = [];

// Récupération de l'ID de l'utilisateur à modifier
// Par défaut, c'est l'utilisateur connecté (gestion du profil)
$userId = $_SESSION['id'];

// Si c'est un admin, il peut modifier n'importe quel utilisateur via l'ID en paramètre
if ($_SESSION['role'] === Role::ADMIN->value && isset($_GET['id'])) {
    $userId = $_GET['id'];
}

// Récupération des informations de l'utilisateur
$query = 'SELECT * FROM users WHERE id = ?';
$req = $pdo->prepare($query);
$req->execute([$userId]);
$user = $req->fetch();

if (! $user) {
    header('Location: user.php');
    exit();
}

// -Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Récupération et nettoyage des données
    $username = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // -Validation du pseudo
    if (empty($username) || ! preg_match('#^[a-zA-Z0-9_]+$#', $username)) {
        $errors['pseudo'] = 'Pseudo non valide';
    } else {
        $query = 'SELECT * FROM users WHERE pseudo = ? AND id != ?';
        $req = $pdo->prepare($query);
        $req->execute([$username, $userId]);

        if ($req->fetch()) {
            $errors['pseudo'] = 'Ce pseudo est déjà pris';
        }
    }

    // Validation de l'email
    if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email non valide';
    } else {
        $query = 'SELECT * FROM users WHERE email = ? AND id != ?';
        $req = $pdo->prepare($query);
        $req->execute([$email, $userId]);

        if ($req->fetch()) {
            $errors['email'] = 'Cet email est déjà utilisé';
        }
    }

    // Validation du mot de passe
    if (! empty($password) && $password !== $confirm_password) {
        $errors['password'] = 'Les mots de passe ne correspondent pas';
    }

    // -Mise à jour des informations de l'utilisateur
    if (empty($errors)) {
        $query = 'UPDATE users SET pseudo = ?, email = ?';
        $params = [$username, $email];

        // Si un nouveau mot de passe est fourni
        if (! empty($password)) {
            $query .= ', password = ?';
            $params[] = password_hash($password, PASSWORD_BCRYPT);
        }

        $query .= ' WHERE id = ?';
        $params[] = $userId;

        $req = $pdo->prepare($query);
        $req->execute($params);

        $success['update'] = 'Profil mis à jour avec succès !';
    }
}

$pageTitle = 'Éditer l\'Administrateur';
ob_start();
require_once 'resources/views/admin/admin-update-profil_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';