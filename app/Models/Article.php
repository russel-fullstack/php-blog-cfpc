<?php

declare(strict_types=1);

require_once __DIR__ . '/Model.php';

class Article extends Model
{

    protected string $table = 'articles';
    public function __construct(
        public readonly int $id = 0,
        public string $title = '',
        public string $slug = '',
        public string $introduction = '',
        public string $content = '',
        public ?string $imagePath = null,
        public string $created_at = '',
        public string $updated_at = '',
        public int $comment_count = 0,
    ) {
        parent::__construct();
    }


    public static function findAll(?int $limit = null, ?int $offset = null, string $searchTerm = ''): array
    {
        $sql = 'SELECT 
       articles.*, 
       (SELECT COUNT(*) FROM comments WHERE comments.article_id = articles.id) AS comment_count
        FROM articles 
        ';
        $instance = new static();

        if (!empty($searchTerm)) {
            $sql .= ' WHERE title LIKE ? OR introduction LIKE ?';
        }
        $sql .= ' ORDER BY created_at DESC';

        if ($limit !== null && $offset !== null) {
            $sql .= ' LIMIT :limit OFFSET :offset';
        }
        $resultats = $instance->pdo->prepare($sql);
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
    public static function countBySlugExcept(int $articleId, string $slug): int
    {
        $instance = new static();
        $query = $instance->pdo->prepare('SELECT COUNT(*) FROM articles WHERE slug = :slug AND id != :id');
        $query->execute(['slug' => $slug, 'id' => $articleId]);
        $count = $query->fetchColumn();
        return (int)$count;
    }



    public static function findBySlug(string $slug): array|false
    {
        $instance = new static();
        $query = $instance->pdo->prepare('SELECT * FROM articles WHERE slug = :slug');
        $query->execute(['slug' => $slug]);
        $count = $query->fetchColumn();

        return $count;
    }
   
    public static function insert(string $title, string $slug, string $introduction, string $content, ?string $imagePath): array|false
    {
        $instance = new static();
        $query = $instance->pdo->prepare('INSERT INTO articles (title, slug, introduction, content, image, created_at) VALUES (:title, :slug, :introduction, :content, :image, NOW())');
        $query->execute([
            'title' => $title,
            'slug' => $slug,
            'introduction' => $introduction,
            'content' => $content,
            'image' => $imagePath
        ]);
        return $query->fetch();
    }
    public static function update(int $articleId, string $title, string $slug, string $introduction, string $content, string $currentImage): array|false
    {
        $instance = new static();
        $query = $instance->pdo->prepare('UPDATE articles SET title = :title, slug = :slug, introduction = :introduction, content = :content, image = :image, updated_at = NOW() WHERE id = :articleId');
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
}

