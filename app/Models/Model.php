<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database/database.php';

class Model
{
    protected PDO $pdo;
    public function __construct()
    {
        $this->pdo = getPDO();
    }
}
