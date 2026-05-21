<?php

declare(strict_types=1);
session_start();
require_once './database/database.php';
require_once 'flash.php';
require_once 'app/helpers.php';



function register( string $pseudo, string $email, string $password, string $confirm_password): string
{

    if (empty($pseudo) || empty($email) || empty($password) || empty($confirm_password)) {
        return "Tous les champs doivent être remplis.";
    }

    if (strlen($pseudo) > 255) return "Votre nom d'utilisateur ne doit pas dépasser 255 caractères.";

    $stmt = authentificateUserByUsername($pseudo);

    if ($stmt) return "Ce nom d'utilisateur est déjà utilisé.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Adresse email invalide.";

    $stmt = authentificateUserByEmail($email);

    if ($stmt) return "Adresse email déjà utilisée !";

    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password)) {
        return "Mot de passe : 8 caractères min. avec une lettre et un chiffre.";
    }
    if ($password !== $confirm_password) return "Les mots de passe ne correspondent pas !";

    insertUser($pseudo, $email, $password);

    return "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $pseudo = strip_tags($_POST['pseudo'] ?? '');
    $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $password    = $_POST['password'] ?? '';
    $confirm_password   = $_POST['confirm_password'] ?? '';

    $result = register($pseudo, $email, $password, $confirm_password);
    if ($result === "success") {
        flash_set('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
        redirect('login.php');
    }

    flash_set('error', $result);
    redirect('register.php');
}

$pageTitle = 'Inscription'; // Titre de la page d'inscription

render('users/register', compact('pageTitle'));
