<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== Role::ADMIN->value) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['add-article'])) {
    $title = clean_input((string) ($_POST['title'] ?? ''));
    $slug = createSlug($title);
    $introduction = clean_input((string) ($_POST['introduction'] ?? ''));
    $content = $_POST['content'];
    $imagePath = null;

    if (empty($title) || empty($introduction) || empty($content)) {
        $error = 'Tous les champs sont requis.';
    }

    $query = $pdo->prepare('SELECT * FROM articles WHERE slug = :slug');
    $query->execute(['slug' => $slug]);
    $count = $query->fetchColumn();
    if ($count > 0) {
        $error = 'Un article avec ce titre existe déjà.';
    } else {
        $query = $pdo->prepare('INSERT INTO articles (title, slug, introduction, content, image, created_at) VALUES (:title, :slug, :introduction, :content, :image, NOW())');
        $query->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':introduction' => $introduction,
            ':content' => $content,
            ':image' => $imagePath
        ]);
        if($query->rowCount() > 0) {
        $_SESSION['success']['update'] = 'Article ajouté avec succès !';
        header('Location: admin-list-article.php');
        exit();
        }
    }

}

$pageTitle = 'Add Articles';
ob_start();
require_once 'resources/views/admin/articles/admin-add-article_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';
