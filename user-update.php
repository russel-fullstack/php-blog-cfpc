<?php

declare(strict_types=1);


session_start();
require_once 'database/database.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';


// Vérification de l'authentification
checkAuth();

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

$user = findUserById((int)$userId);

if (! $user) {
    redirect('user.php');
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


        $userExistUsername = findUserByUsernameExcept($username, (int)$userId);

        if ($userExistUsername) {
            $errors['pseudo'] = 'Ce pseudo est déjà pris';
        }
    }

    // Validation de l'email
    if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email non valide';
    } else {
        $userExistEmail = findUserByEmailExcept($email, (int)$userId);

        if ($userExistEmail) {
            $errors['email'] = 'Cet email est déjà utilisé';
        }
    }

    // Validation du mot de passe
    if (! empty($password) && $password !== $confirm_password) {
        $errors['password'] = 'Les mots de passe ne correspondent pas';
    }

    // -Mise à jour des informations de l'utilisateur
    if (empty($errors)) {
        // Préparation de la requête de base
        $query = 'UPDATE users SET pseudo = :username, email = :email';
        $params = [
            'username' => $username,
            'email' => $email,
            'userId' => $userId
        ];

        // Si un nouveau mot de passe est fourni
        if (! empty($password)) {
            $query .= ', password = :password';
            $params['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $query .= ' WHERE id = :userId';

        $req = $pdo->prepare($query);
        $req->execute($params);

        // Mettre à jour la session si l'utilisateur modifie son propre profil
        if ($userId == $_SESSION['id']) {
            $_SESSION['pseudo'] = $username;
            $_SESSION['email'] = $email;
        }

        $success['update'] = 'Profil mis à jour avec succès !';

        // Rafraîchir les données utilisateur pour l'affichage
        $user = findUserById((int)$userId);
    }
    }

$pageTitle = 'Éditer l\'utilisateur';
render('users/user-update', [
    'pageTitle' => $pageTitle,
    'user' => $user,
    'errors' => $errors,
    'success' => $success
], 'user-layout');
