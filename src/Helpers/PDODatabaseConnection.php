<?php


namespace PHPDojo\Helpers;

use PHPDojo\Helpers\Configuration;
use \PDO;

/**
 * Class DatabaseConnection
 * Helper class for db connections
 *
 * Will be replaced by PDO soon (better Exception handling)
 * @package PHPDojo\Helpers
 */

final class PDODatabaseConnection
{
    private static $instance = null;

    private $db;

    private static function instance() : PDODatabaseConnection {
        if(static::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct() {
        $dsn = Configuration::instance()->getDatabaseTyp() . ':dbname=' . Configuration::instance()->getDatabaseName() .
            ';host=' . Configuration::instance()->getDatabaseHost();
        $user = Configuration::instance()->getDatabaseUser();
        $password = Configuration::instance()->getDatabasePassword();

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try {
            $this->db = new PDO(
                $dsn,
                $user,
                $password,
                $options
            );
        } catch (\PDOException $e) {
            error_log("Connection to database failed!: " . $e->getMessage());
        }
    }

    public static function db() : \PDO{
        return self::instance()->db;
    }

    public static function close() {
        static::instance()->mysqli()->close();
    }
}