<?php
declare(strict_types=1);
session_start();
require_once './database/database.php';
require_once 'flash.php';



function register(PDO $pdo, string $pseudo, string $email, string $password, string $confirm_password): string {

    if(empty($pseudo) || empty($email) || empty($password) || empty($confirm_password)){
        return "Tous les champs doivent être remplis.";
    }

     if (strlen($pseudo) > 255) return "Votre nom d'utilisateur ne doit pas dépasser 255 caractères.";
   $stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = :pseudo");
    $stmt->execute([':pseudo' => $pseudo]);
    if ($stmt->rowCount() > 0) return "Ce nom d'utilisateur est déjà utilisé.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Adresse email invalide.";

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->rowCount() > 0) return "Adresse email déjà utilisée !";

    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password)) {
        return "Mot de passe : 8 caractères min. avec une lettre et un chiffre.";
    }
    if ($password !== $confirm_password) return "Les mots de passe ne correspondent pas !";

    $stmt = $pdo->prepare("INSERT INTO users(pseudo, email, password) VALUES(:pseudo, :email, :password)");
    $stmt->execute([':pseudo' => $pseudo, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);

    return "success";
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
   $pseudo = strip_tags($_POST['pseudo'] ?? '');
    $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $password    = $_POST['password'] ?? '';
    $confirm_password   = $_POST['confirm_password'] ?? '';

 $result = register($pdo, $pseudo, $email, $password, $confirm_password);
     if ($result === "success") {
        flash_set('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
        header("Location: login.php");
        exit();
    }

    flash_set('error', $result);
    header("Location: register.php");
    exit();
}

$pageTitle = 'Inscription'; // Titre de la page d'inscription
ob_start(); // Créer un tampon de sortie pour stocker le contenu de la page d'inscription
require_once 'resources/views/users/register_html.php'; // Inclure la vue de la page d'inscription
$pageContent = ob_get_clean(); // Récupérer le contenu du tampon de sortie et le stocker dans la variable $pageContent
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php'; // Inclure le layout du blog qui affichera le header, le contenu et le footer

?>
