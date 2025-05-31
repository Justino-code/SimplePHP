<?php

namespace SPHP\Core\Connection;

/**
 * Fábrica de conexões de banco de dados.
 */
class ConnectionFactory
{
    /**
     * Cria uma instância da conexão apropriada com base no .env.
     *
     * @return Database
     */
    public static function make(): Database
    {
        $driver = strtolower($_ENV['DB_DRIVER'] ?? 'mysql');

        return match ($driver) {
            'sqlite' => new ConnectionSqlite(),
            'mysql'  => new ConnectionMysql(),
            default  => throw new \Exception("Driver de banco de dados não suportado: $driver"),
        };
    }
}
