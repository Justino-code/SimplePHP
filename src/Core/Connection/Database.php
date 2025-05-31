<?php

namespace SPHP\Core\Connection;

use PDO;

/**
 * Classe abstrata base para conexões de banco de dados.
 */
abstract class Database
{
    /**
     * @var PDO Instância PDO
     */
    protected PDO $pdo;

    /**
     * Retorna a conexão PDO ativa.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Executa uma instrução SQL diretamente.
     *
     * @param string $sql
     * @return int|false
     */
    public function execute(string $sql): int|false
    {
        return $this->pdo->exec($sql);
    }

    /**
     * Prepara uma query SQL.
     *
     * @param string $sql
     * @return \PDOStatement
     */
    public function prepare(string $sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
}
