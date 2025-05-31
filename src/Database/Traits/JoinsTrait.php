<?php

namespace SPHP\Core\Traits;

trait JoinsTrait
{
    protected array $joins = [];

    public function join(string $table, string $first, string $operator, string $second): static
    {
        $this->joins[] = "INNER JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): static
    {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function rightJoin(string $table, string $first, string $operator, string $second): static
    {
        $this->joins[] = "RIGHT JOIN $table ON $first $operator $second";
        return $this;
    }

    protected function compileJoins(): string
    {
        return implode(' ', $this->joins);
    }
}
