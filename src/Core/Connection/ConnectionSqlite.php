<?php

namespace SPHP\Core\Connection;

use PDO;
use PDOException;

/**
 * Classe de conexão com SQLite usando PDO.
 */
class ConnectionSqlite extends Database
{
    public function __construct()
    {
        try {
            $path = $_ENV['DB_PATH'] ?? __DIR__ . '/../../database.sqlite';
            $this->pdo = new PDO('sqlite:' . $path);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erro SQLite: ' . $e->getMessage());
        }
    }
}
