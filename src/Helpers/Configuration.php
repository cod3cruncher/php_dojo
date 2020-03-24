<?php


namespace PHPDojo\Helpers;


final class Configuration
{
    private static $instance = null;

    private function __construct() {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    public static function instance() : Configuration {
        if(static::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function getEnvValue($key) {

    }

    public function getDatabaseHost() {
        return getenv('DATABASE_HOST');
    }

    public function getDatabaseUser() {
        return getenv('DATABASE_USER');
    }

    public function getDatabasePassword() {
        return getenv('DATABASE_PASSWORD');
    }

    public function getDatabaseName() {
        return getenv('DATABASE_NAME');
    }

    public function getDatabaseTyp() {
        return getenv('DATABASE_TYPE');
    }
}