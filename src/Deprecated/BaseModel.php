<?php
namespace SPHP\Deprecated;

use PDO;
use SPHP\Deprecated\Database;

abstract class BaseModel
{
    protected PDO $pdo;
    protected $table;
    protected $primaryKey = 'id';

    protected array $wheres = [];
    protected array $bindings = [];
    protected string $orderBy = '';
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $joins = [];
    protected array $groupBy = [];
    protected array $having = [];

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection();
	 $this->resolveTable();
    }

    private function resolveTable(): void
    {
        // Se já estiver definida manualmente, não sobrescreve
        if (!isset($this->table)) {
            $className = (new \ReflectionClass($this))->getShortName(); // e.g. User
            $this->table = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)); // user 
        }
    }

    // ========== CRUD BÁSICO ==========

    public function all(array $columns = ['*']): array
    {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM {$this->table}";
        return $this->executeQuery($sql);
    }

    public function find(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
{
    try {
        $data = $this->sanitize($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);
	return (int) $this->pdo->lastInsertId();

    } catch (\PDOException $e) {
        // Logar, exibir ou lançar uma exceção personalizada
        error_log("Erro ao inserir em {$this->table}: " . $e->getMessage());
        throw new \Exception("Erro ao inserir registro: " . $e->getMessage());
    }
}


    public function update(int $id, array $data): bool
{
    try {
        $data = $this->sanitize($data);
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

        $data['id'] = $id;
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :id");
        return $stmt->execute($data);

    } catch (\PDOException $e) {
        error_log("Erro ao atualizar em {$this->table}: " . $e->getMessage());
        throw new \Exception("Erro ao atualizar registro: " . $e->getMessage());
    }
}

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    // ========== WHERE / FILTROS ==========

    public function where(string $column, string $operator, mixed $value): static
    {
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orWhere(string $column, string $operator, mixed $value): static
    {
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Novo: Agrupar condições OR com parênteses
    public function whereGroup(callable $callback): static
    {
        // Salva o estado atual
        $originalWheres = $this->wheres;
        $originalBindings = $this->bindings;

        // Limpa temporariamente
        $this->wheres = [];
        $this->bindings = [];

        // Executa o callback (ex: função anônima com orWhere)
        $callback($this);

        // Junta os wheres do grupo
        $groupedWheres = implode(' OR ', $this->wheres);
        $this->wheres = $originalWheres;
        $this->bindings = array_merge($originalBindings, $this->bindings);

        // Adiciona o grupo à cláusula WHERE principal
        $this->wheres[] = "($groupedWheres)";
        return $this;
    }

    public function whereIn(string $column, array $values): static
{
    if (empty($values)) {
        throw new \InvalidArgumentException("O array de valores para whereIn não pode estar vazio.");
    }

    $placeholders = implode(', ', array_fill(0, count($values), '?'));
    $this->wheres[] = "{$column} IN ({$placeholders})";
    $this->bindings = array_merge($this->bindings, $values);
    return $this;
}


    // ========== JOIN ==========

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): static
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    // ========== GROUP BY / HAVING ==========

    public function groupBy(string $column): static
    {
        $this->groupBy[] = $column;
        return $this;
    }

    public function having(string $column, string $operator, mixed $value): static
    {
        $this->having[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    // ========== ORDER / LIMIT / OFFSET ==========

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orderBy = "ORDER BY {$column} {$direction}";
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

    // ========== EXECUÇÃO ==========

    public function get(array $columns = ['*']): array
    {
        $sql = $this->buildSelect($columns);
        return $this->executeQuery($sql);
    }

    public function first(): array|false
    {
        $this->limit(1);
        return $this->get()[0] ?? false;
    }

    protected function executeQuery(string $sql): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========== SQL Builder ==========

    protected function buildSelect(array $columns): string
    {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM {$this->table}";

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        if ($this->groupBy) {
            $sql .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if ($this->having) {
            $sql .= " HAVING " . implode(' AND ', $this->having);
        }

        if ($this->orderBy) {
            $sql .= " {$this->orderBy}";
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    // ========== Utilitários ==========

    protected function sanitize(array $data): array
    {
        return array_map(fn($v) => is_string($v) ? htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8') : $v, $data);
    }

    public function toSql(): string
    {
        return $this->buildSelect(['*']);
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function save(array $data): bool
    {
        return isset($data['id']) ? $this->update($data['id'], $data) : $this->create($data);
    }

public function count(): int
{
    $sql = "SELECT COUNT(*) as count FROM {$this->table}";

    if ($this->joins) {
        $sql .= ' ' . implode(' ', $this->joins);
    }

    if ($this->wheres) {
        $sql .= " WHERE " . implode(' AND ', $this->wheres);
    }

    if ($this->groupBy) {
        $sql .= " GROUP BY " . implode(', ', $this->groupBy);
    }

    if ($this->having) {
        $sql .= " HAVING " . implode(' AND ', $this->having);
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($this->bindings);

    if ($this->groupBy) {
        return count($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    return (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

public function paginate(int $perPage = 10, int $currentPage = 1, array $columns = ['*']): array
{
    // Calcula o offset
    $offset = ($currentPage - 1) * $perPage;

    // Clona o estado atual da model para contar sem aplicar LIMIT/OFFSET
    $clone = clone $this;
    $total = $clone->count();

    // Aplica limite e offset na consulta original
    $this->limit($perPage)->offset($offset);
    $data = $this->get($columns);

    // Calcula total de páginas
    $lastPage = max(1, (int) ceil($total / $perPage));

    return [
        'data' => $data,
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'last_page' => $lastPage
    ];
}

public function increment(string $column, int|float $amount = 1): bool
{
    if (empty($this->wheres)) {
        throw new \Exception("Por segurança, increment requer uma cláusula where.");
    }

    $sql = "UPDATE {$this->table} SET {$column} = {$column} + ?";
    $params = array_merge([$amount], $this->bindings);

    if ($this->wheres) {
        $sql .= " WHERE " . implode(' AND ', $this->wheres);
    }

    $stmt = $this->pdo->prepare($sql);
	//dd('DEBUG', $sql, $params, $stmt->execute($params));
    return $stmt->execute($params);
}

public static function query(): static
{
    return new static();
}

public function lastInsertId(): int
{
    return (int) $this->pdo->lastInsertId();
}



}
