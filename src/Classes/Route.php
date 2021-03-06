<?php

namespace PHPDojo\Classes;


use PHPDojo\Controllers\CrudController;

/**
 * A very simple Router
 * You can setup single routes, or use a CrudController
 * with some predefined routes.
 * See CrudController.php for details
 * the idea was adapted from: https://steampixel.de/einfaches-und-elegantes-url-routing-mit-php/
 * @package PHPDojo\Classes
 */
final class Route implements RouteFacade
{
    private static $instance;

    private $routes;
    private $pathNotFound = null;
    private $methodNotAllowed = null;

    private static function instance(): Route {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct() {
        $this->routes = array();
    }

    /**
     * Used to add a single route, complex expressions
     * are also allowed
     * e.g.
     * add('/foo/', FooController@foo;
     * add('/foo/([0-9]*)/bar', FooController@foobar);
     * @param simple $expression
     * @param the $classAtFunction
     * @param string $method
     */
    public static function add($expression, $classAtFunction, $method = 'get'): void {
        static::instance()->doAdd($expression, $classAtFunction, $method);
    }

    /**
     * This is used for CrudControllers only.
     * All routes for the CrudController operations are automatically defined
     * @param the $uri
     * @param the $controllerName
     * @throws \Exception
     */
    public static function resource($uri, $controllerName): void {
        static::instance()->doRessource($uri, $controllerName);
    }

    /**
     * Call this in your bootstrap file
     * The route is parsed here
     * @param string $basepath
     */
    public static function run($basepath = '/'): void {
        static::instance()->doRun($basepath);
    }

    public static function pathNotFound($function) {
        static::instance()->doPathNotFound($function);
    }

    public static function methodNotAllowed($function) {
        static::instance()->doMethodNotAllowed($function);
    }

    /************* the hooks *************/

    private function doAdd($expression, $classAtFunction, $method): void {
        $classAndFunction = explode('@', $classAtFunction);
        array_push($this->routes, Array(
            'expression' => $expression,
            'class' => $classAndFunction[0],
            'function' => $classAndFunction[1],
            'method' => $method
        ));
    }

    /**
     * The routes for the CrudController
     * @param $uri
     * @param $controllerName
     * @throws \ReflectionException
     */
    private final function doRessource($uri, $controllerName): void {
        $controller = ControllerContainer::instance()->getController($controllerName);
        if (is_a($controller, 'PHPDojo\Controllers\CrudController')) {
            $newUri = '';
            foreach (CrudController::METHOD_NAME_MAP as $key => $method) {
                switch ($key) {
                    case 'index':
                        $newUri = $uri;
                        break;
                    case 'create':
                        $newUri = $uri . '/create';
                        break;
                    case 'show':
                        $newUri = $uri . '/([0-9]*)';
                        break;
                    case 'update':
                        $newUri = $uri . '/update';
                        break;
                    case 'delete':
                        $newUri = $uri . '/delete';
                        break;
                    case 'edit':
                        $newUri = $uri . '/([0-9]*)/edit';
                        break;
                }
                static::add($newUri, $controllerName . '@' . $key, $method);
            }
        }
        else {
            throw new \Exception("You need a CrudController!");
        }
    }

    private function doRun($basepath = '/'): void {
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri
        if (isset($parsed_url['path'])) {
            $path = $parsed_url['path'];
        }
        else {
            $path = '/';
        }
        $method = $_SERVER['REQUEST_METHOD'];
        $path_match_found = false;
        $route_match_found = false;
        foreach ($this->routes as $route) {
            // If the method matches check the path

            // Add basepath to matching string
            if ($basepath != '' && $basepath != '/') {
                $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            }
            // Add 'find string start' automatically
            $route['expression'] = '^' . $route['expression'];
            // Add 'find string end' automatically
            $route['expression'] = $route['expression'] . '$';
            // Check path match
            if (preg_match('#' . $route['expression'] . '#', $path, $matches)) {
                $path_match_found = true;
                // Check method match
                if (strtolower($method) == strtolower($route['method'])) {
                    array_shift($matches);// Always remove first element. This contains the whole string
                    if ($basepath != '' && $basepath != '/') {
                        array_shift($matches);// Remove basepath
                    }
//                  call_user_func_array($route['function'], $matches);
                    $controller = ControllerContainer::instance()->getController($route['class']);

                    //login functionality
                    if (call_user_func_array(array($controller, 'isLoginNeeded'), [])) {
                        if (isset($_SESSION['user']) && $_SESSION['user'] != null) {
                            call_user_func_array(array($controller, $route['function']), $matches);
                        }
                        else {
                            header('Location: /');
                        }
                    }
                    else {
                        call_user_func_array(array($controller, $route['function']), $matches);
                    }
                    $route_match_found = true;
                    break;
                }
            }
        }
        if (!$route_match_found) {
            // But a matching path exists
            if ($path_match_found) {
                header("HTTP/1.0 405 Method Not Allowed");
                if ($this->methodNotAllowed) {
                    call_user_func_array($this->methodNotAllowed, Array($path, $method));
                }
            }
            else {
                header("HTTP/1.0 404 Not Found");
                if ($this->pathNotFound) {
                    call_user_func_array($this->pathNotFound, Array($path));
                }
            }

        }

    }

    private function doPathNotFound($function): void {
        $this->pathNotFound = $function;
    }


    private function doMethodNotAllowed($function): void {
        $this->methodNotAllowed = $function;
    }
}