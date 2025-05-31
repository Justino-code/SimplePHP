<?php

namespace SPHP\Database;

use SPHP\Core\Traits\CrudTrait;
use SPHP\Core\Traits\FiltersTrait;
use SPHP\Core\Traits\JoinsTrait;
use SPHP\Core\Traits\RelationsTrait;
use SPHP\Core\Traits\SoftDeletesTrait;

class QueryBuilder
{
    use CrudTrait, FiltersTrait, JoinsTrait, RelationsTrait, SoftDeletesTrait;

    protected string $table = '';
    protected array $columns = ['*'];
    protected array $wheres = [];
    protected array $bindings = [];
    protected array $joins = [];
    protected array $orders = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected bool $distinct = false;

    protected QueryExecutor $executor;

    public function __construct(string $table = '')
    {
        $this->table = $table;
        $this->executor = new QueryExecutor(\SPHP\Core\Connection\ConnectionManager::get());
    }

    public function setExecutor(QueryExecutor $executor): void
    {
        $this->executor = $executor;
    }

    public function select(array|string $columns): static
    {
        $this->columns = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function distinct(bool $value = true): static
    {
        $this->distinct = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orders[] = [$column, strtoupper($direction)];
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): array
    {
        return $this->executor->fetchAll($this);
    }

    public function first(): ?array
    {
        $this->limit(1);
        return $this->executor->fetchOne($this);
    }

    public function toSql(): string
    {
        $sql = $this->distinct ? 'SELECT DISTINCT ' : 'SELECT ';
        $sql .= implode(', ', $this->columns);
        $sql .= ' FROM ' . $this->table;

        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
            }
        }

        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(' AND ', array_column($this->wheres, 'condition'));
        }

        if (!empty($this->orders)) {
            $orderClauses = array_map(fn($o) => "{$o[0]} {$o[1]}", $this->orders);
            $sql .= ' ORDER BY ' . implode(', ', $orderClauses);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return $sql;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function reset(): void
    {
        $this->columns = ['*'];
        $this->wheres = [];
        $this->bindings = [];
        $this->joins = [];
        $this->orders = [];
        $this->limit = null;
        $this->offset = null;
        $this->distinct = false;
    }
}
