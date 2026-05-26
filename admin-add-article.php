<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';


checkAdmin();

if (isset($_POST['add-article'])) {
    $title = clean_input((string) ($_POST['title'] ?? ''));
    $slug = createSlug($title);
    $introduction = clean_input((string) ($_POST['introduction'] ?? ''));
    $content = $_POST['content'];
    $imagePath = null;

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
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
                $imagePath = $destination;
            } else {
                $error = 'Erreur lors du téléchargement de l\'image.';
            }
        }
    }

    if (empty($title) || empty($introduction) || empty($content)) {
        $error = 'Tous les champs sont requis.';
    } else {
        $count = Article::findBySlug($slug);
        if ($count > 0) {
            $error = 'Un article avec ce titre existe déjà.';
        } else {
            $query = Article::insert($title, $slug, $introduction, $content, $imagePath);
            
            if ($query) {
                flash_set('success', 'Article ajouté avec succès !');
                redirect('admin-list-article.php');
            }
        }
    }
}

$pageTitle = 'Add Articles';

render('admin/articles/admin-add-article', [
    'pageTitle' => $pageTitle
], 'admin-layout');
