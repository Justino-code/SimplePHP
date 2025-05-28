<?php

namespace Src;

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
        $this->db      = $_ENV['DB_NAME'] ?? 'tech_db'; // Defina o nome do banco de dados
        $this->user    = $_ENV['DB_USER'] ?? 'root';
        $this->pass    = $_ENV['DB_PASS'] ?? '';
        $this->charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        // Cria o banco de dados se não existir
        $this->createDatabaseIfNotExists();

        // Conecta ao banco de dados já garantido
        $this->connectToDatabase();

        // Cria as tabelas se não existirem
        $this->createTablesIfNotExists();
    }

    private function createDatabaseIfNotExists()
    {
        try {
            // Conecta sem banco de dados especificado
            $dsn = "mysql:host={$this->host};charset={$this->charset}";
            $pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // Cria o banco se não existir
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->db}` CHARACTER SET {$this->charset} COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            die('Erro ao criar o banco de dados: ' . $e->getMessage());
        }
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

    // Cria as tabelas se não existirem
	private function createTablesIfNotExists()
{
    try {
        $sql = "
            CREATE TABLE IF NOT EXISTS equipa (
                id_equipa INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL UNIQUE,
                cidade VARCHAR(100),
                fundacao DATE,
                escudo VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS jogo (
                id_jogo INT AUTO_INCREMENT PRIMARY KEY,
                id_equipa_casa INT NOT NULL,
                id_equipa_visitante INT NOT NULL,
                data_jogo DATETIME NOT NULL,
                gols_casa INT,
                gols_visitante INT,
                status ENUM('agendado', 'realizado', 'adiado', 'cancelado') DEFAULT 'agendado',
                resultado ENUM('casa', 'visitante', 'empate', 'indefinido') DEFAULT 'indefinido',
                local VARCHAR(100),
                rodada INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_equipa_casa) REFERENCES equipa(id_equipa) ON DELETE CASCADE,
                FOREIGN KEY (id_equipa_visitante) REFERENCES equipa(id_equipa) ON DELETE CASCADE,
                CONSTRAINT check_teams_different CHECK (id_equipa_casa != id_equipa_visitante)
            );

            CREATE TABLE IF NOT EXISTS classificacao (
                id_clas INT AUTO_INCREMENT PRIMARY KEY,
                id_equipa INT,
                pontos INT DEFAULT 0,
                jogos INT DEFAULT 0,
                vitorias INT DEFAULT 0,
                empates INT DEFAULT 0,
                derrotas INT DEFAULT 0,
                gols_pro INT DEFAULT 0,
                gols_contra INT DEFAULT 0,
                saldo_gols INT DEFAULT 0,
                FOREIGN KEY (id_equipa) REFERENCES equipa(id_equipa) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS usuario (
                id_usuario INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin','gestor') DEFAULT 'gestor',
                last_login DATETIME NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ";

        $this->pdo->exec($sql);

        $this->insertAdminIfNotExists();
    } catch (PDOException $e) {
        die('Erro ao criar as tabelas: ' . $e->getMessage());
    }
}
    // Insere um admin padrão, caso ainda não exista
    private function insertAdminIfNotExists()
{
    try {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email");
        $stmt->execute(['email' => 'admin@email.com']);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("
                INSERT INTO usuario (nome, email, password, role, last_login)
                VALUES (:name, :email, :password, :role, :last_login)
            ");
            $stmt->execute([
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => $adminPassword,
                'role' => 'admin',
                'last_login' => null
            ]);
        }
    } catch (PDOException $e) {
        die('Erro ao inserir admin padrão: ' . $e->getMessage());
    }
}
}
?>
