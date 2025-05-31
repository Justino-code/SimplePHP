<?php

namespace SPHP\Core\Traits;

trait CrudTrait
{
    public function insert(array $data): ?int
    {
        $this->compiledSql = $this->buildInsert($data);
        $this->bindings = $data;
        return $this->executeInsert();
    }

    public function update(array $data): int
    {
        $this->compiledSql = $this->buildUpdate($data);
        $this->bindings = array_merge($data, $this->filters);
        return $this->executeUpdateOrDelete();
    }

    public function delete(): int
    {
        $this->compiledSql = $this->buildDelete();
        $this->bindings = $this->filters;
        return $this->executeUpdateOrDelete();
    }

    public function find(int|string $id): ?array
    {
        $this->where("id", $id);
        return $this->first();
    }

    public function get(): array
    {
        $this->compiledSql = $this->buildSelect();
        return $this->useCache
            ? $this->remember('get', fn() => $this->executeSelect())
            : $this->executeSelect();
    }

    public function first(): ?array
    {
        $this->limit(1);
        $this->compiledSql = $this->buildSelect();
        return $this->useCache
            ? $this->remember('first', fn() => $this->executeSelect(true))
            : $this->executeSelect(true);
    }

    public function count(): int
    {
        $this->compiledSql = $this->buildCount();
        $this->bindings = $this->filters;
        $result = $this->executeSelect(true);
        return (int) ($result['count'] ?? 0);
    }
}
