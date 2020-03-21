<?php

namespace PHPDojo\Classes;

final class ControllerContainer
{
    private static $instance = null;
    private $controllers;

    private function __construct()
    {
        $this->controllers = array();
    }

    public function instance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getController(string $name) {
        $name = trim($name);
        if(!array_key_exists($name, $this->controllers)) {
            $controller = new \ReflectionClass('\\PHPDojo\\Controllers\\' .$name);
            $this->controllers[$name] = $controller->newInstance();
        }
        return $this->controllers[$name];
    }

    /**
     * we don´t want clones (no second instance)
     */
    private function __clone()
    {

    }

    /**
     * and no deserialization (could create second instance)
     */
    private function __wakeup()
    {

    }
}
?>