<?php

namespace Src;

use PDO;
use PDOException;

/**
 * Classe responsável por gerenciar a conexão com o banco de dados.
 */
class Database
{
    /**
     * @var string Host do banco de dados
     */
    private $host;

    /**
     * @var string Nome do banco de dados
     */
    private $db;

    /**
     * @var string Nome do usuário do banco de dados
     */
    private $user;

    /**
     * @var string Senha do banco de dados
     */
    private $pass;

    /**
     * @var string Charset da conexão
     */
    private $charset;

    /**
     * @var PDO Instância da conexão PDO
     */
    private $pdo;

    /**
     * Construtor da classe Database.
     * Inicializa a conexão com o banco.
     */
    public function __construct()
    {
        $this->host    = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db      = $_ENV['DB_NAME'] ?? 'tech_db';
        $this->user    = $_ENV['DB_USER'] ?? 'root';
        $this->pass    = $_ENV['DB_PASS'] ?? '';
        $this->charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        $this->connect();
    }

    /**
     * Estabelece a conexão com o banco de dados.
     *
     * @return void
     */
    private function connect(): void
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

    /**
     * Retorna a instância PDO ativa.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Executa uma query SQL direta.
     *
     * @param string $sql
     * @return bool|int Retorna false em erro, ou número de linhas afetadas
     */
    public function execute(string $sql): bool|int
    {
        try {
            return $this->pdo->exec($sql);
        } catch (PDOException $e) {
            die('Erro ao executar a query: ' . $e->getMessage());
        }
    }

    /**
     * Prepara uma instrução SQL.
     *
     * @param string $sql
     * @return \PDOStatement
     */
    public function prepare(string $sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
}
