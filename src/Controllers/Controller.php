<?php
namespace PHPDojo\Controllers;

/**
 * Class Controller
 * Base class for all Controllers
 * @package PHPDojo\Controllers
 */
abstract class Controller {

    public abstract function index() : void;

    /**
     * This method is used to check if the routes
     * are only callable by a logged in user or not
     *
     * @return bool if login is needed to access
     */
    public abstract function isLoginNeeded() : bool;



}