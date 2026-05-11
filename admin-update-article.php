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
$article = [];
$currentImage = null;

if (isset($_GET['id'])) {
    $articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $sql = 'SELECT * FROM articles WHERE id = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$articleId]);
    $article = $query->fetch();

    $title = $article['title'] ?? '';
    $slug = $article['slug'] ?? '';
    $introduction = $article['introduction'] ?? '';
    $content = $article['content'] ?? '';
    $currentImage = $article['image'] ?? null;
}

if (isset($_POST['update'])) {
    $title = clean_input((string) filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW));
    $slug = createSlug($title);
    $introduction = clean_input((string) filter_input(INPUT_POST, 'introduction', FILTER_UNSAFE_RAW));
    $content = $_POST['content'];
    $error = null;

    if (empty($title) || empty($introduction) || empty($content)) {
        $error = 'Tous les champs sont requis.';
    }

    if (!$error && !empty($_FILES['a_image']['name']) && $_FILES['a_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['a_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $path = 'storage/articles/';

        $error = match (true) {
            !in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) => 'Format de fichier non autorisé.',
            $file['size'] > 2 * 1024 * 1024 => 'Le fichier est trop volumineux (max 2MB).',
            default => null
        };

        if (!$error) {
            if (!is_dir($path)) mkdir($path, 0755, true);
            $filename = uniqid('article_') . '.' . $ext;
            $destination = $path . $filename;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                if ($currentImage && file_exists($currentImage)) unlink($currentImage);
                $currentImage = $destination;
            } else {
                $error = 'Erreur lors du téléchargement de l\'image.';
            }
        }
    }

    if (!$error) {
        $query = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE slug = :slug AND id != :id');
        $query->execute(['slug' => $slug, 'id' => $articleId]);
        $count = $query->fetchColumn();

        if ($count > 0) {
            $error = 'Un article avec ce titre existe déjà.';
        } else {
            $query = $pdo->prepare('UPDATE articles SET title = :title, slug = :slug, introduction = :introduction, content = :content, image = :image, updated_at = NOW() WHERE id = :articleId');
            $query->execute([
                'title' => $title,
                'slug' => $slug,
                'introduction' => $introduction,
                'content' => $content,
                'image' => $currentImage,
                'articleId' => $articleId,
            ]);

            if ($query->rowCount() > 0) {
                flash_set('success', 'Article mis à jour avec succès !');
                header('Location: admin-list-article.php');
                exit();
            } else {
                flash_set('info', 'Aucun changement détecté.');
                header('Location: admin-list-article.php');
                exit();
            }
        }
    }
    
    if ($error) {
        flash_set('error', $error);
    }
}
ob_start();
require_once 'resources/views/admin/articles/admin-update-article_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';
