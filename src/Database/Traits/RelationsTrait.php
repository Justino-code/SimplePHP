<?php

namespace SPHP\Database\Traits;

trait RelationsTrait
{
    protected string $defaultModelNamespace = 'App\\Models\\';

    // hasOne: Ex: User hasOne Profile
    public function hasOne(string $related, string $foreignKey, string $localKey = 'id'): ?object
    {
        $relatedModel = $this->resolveModel($related);
        return $relatedModel->where($foreignKey, $this->{$localKey})->first();
    }

    // hasMany: Ex: Team hasMany Players
    public function hasMany(string $related, string $foreignKey, string $localKey = 'id'): array
    {
        $relatedModel = $this->resolveModel($related);
        return $relatedModel->where($foreignKey, $this->{$localKey})->get();
    }

    // belongsTo: Ex: Post belongsTo User
    public function belongsTo(string $related, string $foreignKey, string $ownerKey = 'id'): ?object
    {
        $relatedModel = $this->resolveModel($related);
        return $relatedModel->where($ownerKey, $this->{$foreignKey})->first();
    }

    // belongsToMany: Ex: Post belongsToMany Tag via post_tag
    public function belongsToMany(
        string $related,
        string $pivotTable,
        string $foreignPivotKey,
        string $relatedPivotKey,
        string $localKey = 'id',
        string $relatedKey = 'id'
    ): array {
        $db = $this->db(); // método da Model base

        $relatedModel = $this->resolveModel($related);
        $relatedTable = $relatedModel->getTable();

        $localValue = $this->{$localKey};

        $sql = "
            SELECT r.*
            FROM {$pivotTable} p
            JOIN {$relatedTable} r ON r.{$relatedKey} = p.{$relatedPivotKey}
            WHERE p.{$foreignPivotKey} = ?
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([$localValue]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($row) => $relatedModel->fill($row), $rows);
    }

    // Resolve o nome do model para instância completa
    protected function resolveModel(string $model): object
    {
        if (!str_contains($model, '\\')) {
            $model = $this->defaultModelNamespace . $model;
        }

        return new $model;
    }
}
