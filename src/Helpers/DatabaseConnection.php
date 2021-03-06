<?php


namespace PHPDojo\Helpers;

use PHPDojo\Helpers\Configuration;

/**
 * Class DatabaseConnection
 * Helper class for db connections
 *
 * Will be replaced by PDO soon (better Exception handling)
 * @package PHPDojo\Helpers
 */

final class DatabaseConnection
{
    private static $instance = null;

    private $mysqli;

    private static function instance() : DatabaseConnection {
        if(static::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct() {
        $this->mysqli = new \mysqli(
            Configuration::instance()->getDatabaseHost(),
            Configuration::instance()->getDatabaseUser(),
            Configuration::instance()->getDatabasePassword(),
            Configuration::instance()->getDatabaseName()
        );
    }

    public static function mysqli() {
        return self::instance()->mysqli;
    }

    public static function close() {
        static::instance()->mysqli()->close();
    }
}