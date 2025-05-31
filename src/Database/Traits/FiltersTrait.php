<?php

namespace SPHP\Core\Traits;

trait FiltersTrait
{
    protected array $filters = [];
    protected array $wheres = [];

    public function where(string $column, mixed $operatorOrValue, mixed $value = null): static
    {
        if ($value === null) {
            $value = $operatorOrValue;
            $operator = '=';
        } else {
            $operator = $operatorOrValue;
        }

        $param = $this->bindKey($column);
        $this->filters[$param] = $value;
        $this->wheres[] = ["AND", "$column $operator :$param"];
        return $this;
    }

    public function orWhere(string $column, mixed $operatorOrValue, mixed $value = null): static
    {
        if ($value === null) {
            $value = $operatorOrValue;
            $operator = '=';
        } else {
            $operator = $operatorOrValue;
        }

        $param = $this->bindKey($column);
        $this->filters[$param] = $value;
        $this->wheres[] = ["OR", "$column $operator :$param"];
        return $this;
    }

    public function whereIn(string $column, array $values): static
    {
        $keys = [];
        foreach ($values as $i => $val) {
            $key = $this->bindKey($column . $i);
            $this->filters[$key] = $val;
            $keys[] = ":$key";
        }

        $this->wheres[] = ["AND", "$column IN (" . implode(', ', $keys) . ")"];
        return $this;
    }

    public function whereNull(string $column): static
    {
        $this->wheres[] = ["AND", "$column IS NULL"];
        return $this;
    }

    public function whereNotNull(string $column): static
    {
        $this->wheres[] = ["AND", "$column IS NOT NULL"];
        return $this;
    }

    protected function compileWheres(): string
    {
        if (empty($this->wheres)) {
            return '';
        }

        $sql = 'WHERE ';
        $first = true;
        foreach ($this->wheres as [$logic, $clause]) {
            $sql .= ($first ? '' : " $logic ") . $clause;
            $first = false;
        }

        return $sql;
    }

    protected function bindKey(string $column): string
    {
        return str_replace('.', '_', $column) . '_' . count($this->filters);
    }
}
