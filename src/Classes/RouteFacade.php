<?php

namespace PHPDojo\Classes;

interface RouteFacade
{
    /**
     * adds a single route
     * e.g. add('/test','TestController@show');
     *  -> calls the Method show() on the TestController for /test
     * you can also add complex expressions like:
     * add('/test/([0-9]*)/edit', TestController@edit); which will
     * call the edit(id) method on TestController and pass the given value
     * as id.
     *
     * @param $expression simple expression like /test, or complex /test/([0-9]*)/edit
     * @param $classAtFunction  the class and function separated by @, e.g. 'TestController@show'
     * @param string $method the request method: get, post, put ....
     * @return nothing
     */
    public static function add($expression, $classAtFunction, $method = 'get') : void;

    /**
     * creates routes for a CRUD Controller:
     * resource('/users', 'UserController');
     * creates the following routes:
     * Verb     URI             Action
     * GET      /users          index
     * GET      /users/create   create
     * POST     /users          store
     *
     * @param $uri the uri
     * @param $controllerName the name of the CrudController
     * @return nothing
     */
    public static function resource($uri, $controllerName) : void;

    /**
     * call this at your bootstrap file to do the routing
     * @param string $basepath
     * @return mixed
     */
    public static function run($basepath = '/') : void;
}