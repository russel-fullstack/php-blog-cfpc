<?php

declare(strict_types=1);

require_once __DIR__ . '/Model.php';

class User extends Model
{
    protected string $table = 'users';
    public function __construct(
        public readonly int $id = 0,
        public string $pseudo = '',
        public string $email = '',
        public string $password = '',
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


    public static function findAll(): array
    {
        $sql = "SELECT * FROM users";
        $instance = new static();
        $query = $instance->pdo->prepare($sql);
        $query->execute();
        $users = $query->fetchAll();

        return $users;
    }
    public static function findByUsernameExcept(string $username, int $userId): array|false
    {
        $instance = new static();
        $query = 'SELECT * FROM users WHERE pseudo = :username AND id != :userId';
        $req = $instance->pdo->prepare($query);
        $req->execute([
            ':username' => $username,
            ':userId' => $userId
        ]);
        return $req->fetch();
    }
    public static function findByEmailExcept(string $email, int $userId): array|false
    {
        $instance = new static();
        $query = 'SELECT * FROM users WHERE email = :email AND id != :userId';
        $req = $instance->pdo->prepare($query);
        $req->execute([
            ':email' => $email,
            ':userId' => $userId
        ]);
        return $req->fetch();
    }

    public static function insert(string $pseudo, string $email, string $password): bool
    {
        $instance = new static();
        $stmt = $instance->pdo->prepare("INSERT INTO users(pseudo, email, password) VALUES(:pseudo, :email, :password)");
        return $stmt->execute([':pseudo' => $pseudo, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);
    }

    public static function update(int $userId, string $username, string $email, string $password): bool
    {
        $instance = new static();
        $passwordhash = password_hash($password, PASSWORD_BCRYPT);
        $query = 'UPDATE users SET pseudo = :username, email = :email , password = :password  WHERE id = :userId';
        $params = [
            'username' => $username,
            'email' => $email,
            'userId' => $userId,
            'password' => $passwordhash
        ];
        $req = $instance->pdo->prepare($query);
        return $req->execute($params);
    }
    public static function findWithCommentCount()
    {
        $instance = new static();
        $usersQuery = $instance->pdo->query('
    SELECT u.id, u.pseudo, COUNT(c.id) AS comment_count
    FROM users u
    LEFT JOIN comments c ON u.id = c.user_id
    GROUP BY u.id
');
        return $usersQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByEmail(string $email): array|false
    {
        $instance = new static();
        $stmt = $instance->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return  $stmt->fetch();
    }
    public static function findByUsername(string $username): array|false
    {
        $instance = new static();
        $stmt = $instance->pdo->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
        $stmt->execute([':pseudo' => $username]);
        return  $stmt->fetch();
    }
}
