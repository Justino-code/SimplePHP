<?php

namespace SPHP\Core\Connection;

use PDO;
use PDOException;

/**
 * Classe de conexão com MySQL usando PDO.
 */
class ConnectionMysql extends Database
{
    public function __construct()
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $_ENV['DB_HOST'] ?? 'localhost',
                $_ENV['DB_NAME'] ?? 'test',
                $_ENV['DB_CHARSET'] ?? 'utf8mb4'
            );

            $this->pdo = new PDO($dsn, $_ENV['DB_USER'] ?? 'root', $_ENV['DB_PASS'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            die('Erro MySQL: ' . $e->getMessage());
        }
    }
}
