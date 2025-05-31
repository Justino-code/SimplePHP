<?php
namespace SPHP\Core\Connection;

class ConnectionManager
{
    private static ?Database $instance = null;

    public static function get(): Database
    {
        if (is_null(self::$instance)) {
            self::$instance = ConnectionFactory::make();
        }

        return self::$instance;
    }
}
