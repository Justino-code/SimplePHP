<?php

namespace SPHP\Deprecated;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $charset;
    private $pdo;

    public function __construct()
    {
        $this->host    = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db      = $_ENV['DB_NAME'] ?? 'sphp1'; // Defina o nome do banco de dados
        $this->user    = $_ENV['DB_USER'] ?? 'root';
        $this->pass    = $_ENV['DB_PASS'] ?? '';
        $this->charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        $this->connectToDatabase();
    }

    private function connectToDatabase()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
?>
