<?php

declare(strict_types=1);
session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/helpers.php';

// /**
//  * Authenticate a user
//  */
function authenticateUser(PDO $pdo, string $email, string $password): string {
    if (empty($email) || empty($password)) {
        return "Tous les champs doivent être complétés !";
    }

    $user = User::findByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
        return "identifiants incorrects !";
    }

    // Set session variables
    $_SESSION['auth'] = true;
    $_SESSION['id'] = $user['id'];
    $_SESSION['pseudo'] = $user['pseudo'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    return "success";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = authenticateUser($pdo, $email, $password);

    if ($result === "success") {
        flash_set('success', "Heureux de vous revoir " . $_SESSION['pseudo'] . " !");
        
        // Redirect based on role or to index
        if ($_SESSION['role'] === 'admin') {
            redirect("admin.php");
        } else {
            redirect("user-dashboard.php");
        }
        exit();
    } else {
        flash_set('error', $result);
        redirect('login.php');
    }
}




$pageTitle = 'Connexion';
render('users/login', compact('pageTitle'));