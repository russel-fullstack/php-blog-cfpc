<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database/database.php';

/**
 * Récupère tous les articles de la base de données
 *
 * Un tableau d'articles
 */



function countArticlesBySlugExcept(int $articleId, string $slug): int
{
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE slug = :slug AND id != :id');
    $query->execute(['slug' => $slug, 'id' => $articleId]);
    $count = $query->fetchColumn();
    return (int)$count;
}

function findAllArticles(?int $limit = null, ?int $offset = null, string $searchTerm = ''): array
{
    $sql = 'SELECT 
       articles.*, 
       (SELECT COUNT(*) FROM comments WHERE comments.article_id = articles.id) AS comment_count
        FROM articles 
        ';
    $pdo = getPdo();

    if (!empty($searchTerm)) {
        $sql .= ' WHERE title LIKE ? OR introduction LIKE ?';
    }
    $sql .= ' ORDER BY created_at DESC';

    if ($limit !== null && $offset !== null) {
        $sql .= ' LIMIT :limit OFFSET :offset';
    }
    $resultats = $pdo->prepare($sql);
    if (!empty($searchTerm)) {
        $resultats->bindValue(1, '%' . $searchTerm . '%');
        $resultats->bindValue(2, '%' . $searchTerm . '%');
    }
    if ($limit !== null && $offset !== null) {
        $resultats->bindValue(':limit', $limit, PDO::PARAM_INT);
        $resultats->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $resultats->execute();
    return  $resultats->fetchAll();
}

function findArticle(int $id): array|false
{
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
    $query->execute([':id' => $id]);
    $article = $query->fetch();

    return $article;
}

function findArticleBySlug(string $slug): array|false
{
    $pdo = getPdo();
    $query = $pdo->prepare('SELECT * FROM articles WHERE slug = :slug');
    $query->execute(['slug' => $slug]);
    $count = $query->fetchColumn();

    return $count;
}
function deleteArticle(int $id): array|false
{
    $pdo = getPdo();
    $query = $pdo->prepare('DELETE FROM articles WHERE id = :id');
    $query->execute([':id' => $id]);
    return $query->fetch();
}
function insertArticle(string $title, string $slug, string $introduction, string $content, string $imagePath): array|false
{
    $pdo = getPdo();
    $query = $pdo->prepare('INSERT INTO articles (title, slug, introduction, content, image, created_at) VALUES (:title, :slug, :introduction, :content, :image, NOW())');
    $query->execute([
        'title' => $title,
        'slug' => $slug,
        'introduction' => $introduction,
        'content' => $content,
        'image' => $imagePath
    ]);
    return $query->fetch();
}
function updateArticle(int $articleId, string $title, string $slug, string $introduction, string $content, string $currentImage): array|false
{
    $pdo = getPdo();
    $query = $pdo->prepare('UPDATE articles SET title = :title, slug = :slug, introduction = :introduction, content = :content, image = :image, updated_at = NOW() WHERE id = :articleId');
    $query->execute([
        'title' => $title,
        'slug' => $slug,
        'introduction' => $introduction,
        'content' => $content,
        'image' => $currentImage,
        'articleId' => $articleId,
    ]);
    return $query->fetch();
}
