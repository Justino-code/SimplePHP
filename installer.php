<?php
require_once __DIR__ . '/vendor/autoload.php';

use SPHP\Deprecated\CreateDatabaseAndTables;

class Installer {
    use CreateDatabaseAndTables;

    public function __construct() {
        $this->loadEnv();
    }

    public function run(string $task = 'all'): void {
        if ($task === 'schema' || $task === 'all') {
            $this->createDatabaseIfNotExists();
            $this->connectToDatabase();
            $this->createTablesIfNotExistsFromFile(__DIR__ . '/sql/schema.sql');
        }

        if ($task === 'admin') {
            $this->connectToDatabase();
            $this->insertAdminIfNotExists();
        }
    }

    private function loadEnv(): void {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
    }

    private function connectToDatabase(): void {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}";
        $this->pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}

// Captura argumento da linha de comando
$task = $argv[1] ?? 'all';

echo "🔧 Executando instalação: tarefa '{$task}'...\n";
$installer = new Installer();
$installer->run($task);
echo "✅ Tarefa concluída com sucesso.\n";
