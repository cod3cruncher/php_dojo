<?php

namespace PHPDojo\Classes;

/**
 * Class ControllerContainer
 * @package PHPDojo\Classes
 *
 * TThis class is responsible for managing the controllers. Controllers are instantiated independently
 * and can be called up by their names
 *
 * SINGLETON PATTERN
 */
final class ControllerContainer
{
    private static $instance = null;
    private $controllers;

    //singleton
    private function __construct()
    {
        $this->controllers = array();
    }

    public static function instance() : ControllerContainer
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Returns a controller by name
     * Each controller is only instantiated once
     * @param string $name of the controller
     * @return mixed the controller
     * @throws \ReflectionException if there exists no controller class for the name
     */
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
    {}

    /**
     * and no deserialization (could create second instance)
     */
    private function __wakeup()
    {}
}
?>