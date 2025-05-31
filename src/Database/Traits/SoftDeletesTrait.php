<?php

namespace SPHP\Database\Traits;

use DateTime;

trait SoftDeletesTrait
{
    protected bool $withTrashed = false;
    protected bool $onlyTrashed = false;

    // Marcar para incluir registros deletados nas consultas
    public function withTrashed(): static
    {
        $this->withTrashed = true;
        return $this;
    }

    // Marcar para retornar apenas registros deletados
    public function onlyTrashed(): static
    {
        $this->onlyTrashed = true;
        return $this;
    }

    // Sobrescrever o delete para soft delete
    public function delete(int|string|null $id = null): bool
    {
        $this->resetQuery();

        if ($id !== null) {
            $this->where("{$this->primaryKey}", $id);
        }

        $deletedAt = (new DateTime())->format('Y-m-d H:i:s');

        return $this->update([
            'deleted_at' => $deletedAt
        ]);
    }

    // Restaurar um registro deletado logicamente
    public function restore(int|string|null $id = null): bool
    {
        $this->resetQuery();

        if ($id !== null) {
            $this->where("{$this->primaryKey}", $id);
        }

        return $this->update([
            'deleted_at' => null
        ]);
    }

    // Modifica a cláusula WHERE ao construir a query
    protected function applySoftDeleteConstraint(): void
    {
        if (property_exists($this, 'softDelete') && $this->softDelete === true) {
            if (!$this->withTrashed && !$this->onlyTrashed) {
                $this->whereNull('deleted_at');
            } elseif ($this->onlyTrashed) {
                $this->whereNotNull('deleted_at');
            }
        }
    }
}
