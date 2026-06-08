<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database/database.php';

class Model
{
    protected PDO $pdo;
    protected string $table = "";
    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public static function count(): int
    {
        $instance = new static();
        $query = $instance->pdo->prepare("SELECT COUNT(*) FROM {$instance->table}");
        $query->execute();
        return (int)$query->fetchColumn();
    }


    public static function find(int $id): array
    {
        $instance = new static();
        $query = $instance->pdo->prepare("SELECT * FROM {$instance->table} WHERE id = :id");
        $query->execute(['id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public static function delete(int $id): array|false
    {
        $instance = new static();
        $query = $instance->pdo->prepare("DELETE FROM {$instance->table} WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
