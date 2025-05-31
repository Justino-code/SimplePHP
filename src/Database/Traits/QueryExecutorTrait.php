<?php

namespace SPHP\Core\Traits;

use SPHP\Core\Connection\ConnectionManager;
use PDO;
use PDOException;

trait QueryExecutorTrait
{
    protected string $compiledSql = '';
    protected array $bindings = [];

    protected function executeSelect(bool $single = false): mixed
    {
        $pdo = ConnectionManager::get();
        $stmt = $pdo->prepare($this->compiledSql);

        try {
            $stmt->execute($this->bindings);
            return $single ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleQueryException($e);
            return $single ? null : [];
        }
    }

    protected function executeInsert(): ?int
    {
        $pdo = ConnectionManager::get();
        $stmt = $pdo->prepare($this->compiledSql);

        try {
            $stmt->execute($this->bindings);
            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->handleQueryException($e);
            return null;
        }
    }

    protected function executeUpdateOrDelete(): int
    {
        $pdo = ConnectionManager::get();
        $stmt = $pdo->prepare($this->compiledSql);

        try {
            $stmt->execute($this->bindings);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->handleQueryException($e);
            return 0;
        }
    }

    protected function handleQueryException(PDOException $e): void
    {
        if ($_ENV['APP_DEBUG'] === 'true') {
            echo '<pre><strong>Query Error:</strong> ' . $e->getMessage() . "\n";
            echo 'SQL: ' . $this->compiledSql . "\n";
            echo 'Bindings: ' . print_r($this->bindings, true) . '</pre>';
        } else {
            error_log('SQL Error: ' . $e->getMessage());
        }
    }
}
