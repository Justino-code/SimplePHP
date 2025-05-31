<?php

namespace SPHP\Database;

use PDO;
use PDOStatement;
use PDOException;

class QueryExecutor
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAll(QueryBuilder $builder): array
    {
        $stmt = $this->prepareAndExecute($builder);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne(QueryBuilder $builder): ?array
    {
        $stmt = $this->prepareAndExecute($builder);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function fetchColumn(QueryBuilder $builder): mixed
    {
        $stmt = $this->prepareAndExecute($builder);
        return $stmt->fetchColumn();
    }

    public function execute(QueryBuilder $builder): bool
    {
        $stmt = $this->prepareAndExecute($builder);
        return $stmt->rowCount() > 0;
    }

    protected function prepareAndExecute(QueryBuilder $builder): PDOStatement
    {
        $sql = $builder->toSql();
        $bindings = $builder->getBindings();

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($bindings);
            return $stmt;
        } catch (PDOException $e) {
            if ($_ENV['APP_DEBUG'] === 'true') {
                echo '<pre><strong>Query Error:</strong> ' . $e->getMessage() . "\n";
                echo 'SQL: ' . $sql . "\n";
                echo 'Bindings: ' . print_r($bindings, true) . '</pre>';
            } else {
                error_log('SQL Error: ' . $e->getMessage());
            }
            throw new \RuntimeException("Erro ao executar query: " . $e->getMessage());
        }
    }
}
