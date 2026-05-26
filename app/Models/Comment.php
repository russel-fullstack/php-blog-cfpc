<?php

declare(strict_types=1);

require_once __DIR__ . '/Model.php';

class Comment extends Model
{
    public function __construct(
        public readonly int $id = 0,
        public string $content = '',
        public string $created_at = '',
        public int $article_id = 0,
        public int $user_id = 0,
        public string $username = '',
        public string $article_title = '',
        public string $article_slug = '',
    ) {
        parent::__construct();
    }



    public static function count(): int
    {
        $instance = new self();
        return (int)$instance->pdo->query('SELECT COUNT(*)FROM comments')->fetchColumn();
    }
    public static function find(int $id): array
    {
        $instance = new self();
        $query = $instance->pdo->prepare('SELECT * FROM comments WHERE id = :id');
        $query->execute([':id' => $id]);
        $row = $query->fetch();

        return $row;
    }
    public static function findByArticle(int $article_id): array
    {
        $instance = new self();
        $sql = 'SELECT comments.*, users.pseudo
            FROM comments
            JOIN users 
            ON comments.user_id = users.id
            WHERE article_id= :article_id';

        $query = $instance->pdo->prepare($sql);
        $query->execute(compact('article_id'));
        return  $query->fetchAll();
    }
     public static function insert(string $content, int $user_auth, int $article_id): bool
    {
        $pdo = getPdo();
        $query = $pdo->prepare('INSERT INTO comments (content, article_id, user_id, created_at) VALUES (:content, :article_id, :user_auth, NOW())');
        return $query->execute(compact('content', 'article_id', 'user_auth'));
    }

     public static function delete(int $comment_id): bool
    {
        $pdo = getPdo();
        $query = $pdo->prepare('DELETE FROM comments WHERE id = :comment_id');
        return $query->execute(['comment_id' => $comment_id]);
    }

     public static function findByUser(int $userId)
    {
        $pdo = getPdo();
        $commentsQuery = $pdo->prepare('
        SELECT c.id, c.content, c.created_at, a.id AS article_id, a.title AS article_title, a.slug AS article_slug
        FROM comments c
        LEFT JOIN articles a ON c.article_id = a.id
        WHERE c.user_id = :user_id
    ');
        $commentsQuery->execute(['user_id' => $userId]);
        return $commentsQuery->fetchAll(PDO::FETCH_ASSOC);
    }
}
