<?php

namespace SPHP\\Deprecated;

use PDO;
use PDOException;

trait CreateDatabaseAndTables
{
    protected ?PDO $pdo = null;

    protected function createDatabaseIfNotExists(): void
    {
        try {
            $dsn = "mysql:host={$_ENV['DB_HOST']};charset={$_ENV['DB_CHARSET']}";
            $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$_ENV['DB_NAME']}` CHARACTER SET {$_ENV['DB_CHARSET']} COLLATE utf8mb4_unicode_ci");
            echo "✅ Banco de dados criado (ou já existente).\n";
        } catch (PDOException $e) {
            exit("❌ Erro ao criar banco de dados: " . $e->getMessage() . "\n");
        }
    }

    protected function createTablesIfNotExistsFromFile(string $sqlFile): void
    {
        if (!file_exists($sqlFile)) {
            exit("❌ Arquivo SQL não encontrado: {$sqlFile}\n");
        }

        try {
            $sql = file_get_contents($sqlFile);
            $this->pdo->exec($sql);
            echo "✅ Tabelas criadas com sucesso.\n";
        } catch (PDOException $e) {
            exit("❌ Erro ao criar tabelas: " . $e->getMessage() . "\n");
        }
    }

    protected function insertAdminIfNotExists(): void
    {
        if (!$this->pdo) {
            exit("❌ Conexão PDO não definida.\n");
        }

        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email");
            $stmt->execute(['email' => 'admin@email.com']);
            $count = (int)$stmt->fetchColumn();

            if ($count === 0) {
                $password = password_hash('admin123', PASSWORD_BCRYPT);
                $stmt = $this->pdo->prepare("
                    INSERT INTO usuario (nome, email, password, role, last_login)
                    VALUES (:nome, :email, :password, :role, NULL)
                ");
                $stmt->execute([
                    'nome' => 'Admin',
                    'email' => 'admin@email.com',
                    'password' => $password,
                    'role' => 'admin'
                ]);
                echo "✅ Admin padrão inserido.\n";
            } else {
                echo "ℹ️ Admin já existente. Nenhuma alteração feita.\n";
            }
        } catch (PDOException $e) {
            exit("❌ Erro ao inserir admin: " . $e->getMessage() . "\n");
        }
    }
}
