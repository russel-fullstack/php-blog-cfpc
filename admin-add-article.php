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

    if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
       $file = $_FILES['image'];
       $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
       $path = 'storage/articles/';

       $error = match (true) {
           !in_array($ext, ['jpg', 'jpeg', 'png', 'gif','webp']) => 'Format de fichier non autorisé.',
           $file['size'] > 2 * 1024 * 1024 => 'Le fichier est trop volumineux (max 2MB).',
           default => null
       };
       if(!$error) {
        if(!is_dir($path)) mkdir($path, 0755, true);
           $filename = uniqid('article_') . '.' . $ext;
           $destination = $path . $filename;
           if (move_uploaded_file($file['tmp_name'], $destination)) {
               $imagePath = $destination;
           } else {
               $error = 'Erreur lors du téléchargement de l\'image.';
           }
        }
        
    }

    if (empty($title) || empty($introduction) || empty($content)) {
        $error = 'Tous les champs sont requis.';
    }else {
        $query = $pdo->prepare('SELECT * FROM articles WHERE slug = :slug');
    $query->execute(['slug' => $slug]);
    $count = $query->fetchColumn();
    if ($count > 0) {
        $error = 'Un article avec ce titre existe déjà.';
    } else {
        $query = $pdo->prepare('INSERT INTO articles (title, slug, introduction, content, image, created_at) VALUES (:title, :slug, :introduction, :content, :image, NOW())');
        $query->execute([
            'title' => $title,
            'slug' => $slug,
            'introduction' => $introduction,
            'content' => $content,
            'image' => $imagePath
        ]);
        if($query->rowCount() > 0) {
        flash_set('success','Article ajouté avec succès !');
        header('Location: admin-list-article.php');
        exit();
        }
    }
    }

}

$pageTitle = 'Add Articles';
ob_start();
require_once 'resources/views/admin/articles/admin-add-article_html.php';
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php';
