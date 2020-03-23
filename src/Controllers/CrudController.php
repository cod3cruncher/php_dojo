<?php

namespace PHPDojo\Controllers;

use phpDocumentor\Reflection\Types\Array_;

abstract class CrudController extends Controller
{
     public const METHOD_NAME_MAP = [
         'index' => 'GET',
        'create' => 'GET',
        'store' => 'POST',
        'show' => 'GET',
        'edit' => 'GET',
        'update' => 'POST',
        'delete' => 'GET'
    ];

    public final function getMethodType($methodName)
    {
        if (array_key_exists($methodName, static::METHOD_NAME_MAP)) {
            return static::METHOD_NAME_MAP[$methodName];
        }
        else {

            throw new \Exception("Invalid method name given: " . $methodName);
        }
    }

    public function __construct()
    {
    }

    private function checkMethodType($functionName, $type)
    {
        if (strtolower($this->getMethodType($functionName)) != strtolower($type)) {
            throw new \Exception("Wrong type is used!");
        }
    }


    /**
     * GET method, /url/create/  create action
     * @return mixed
     */
    public final function create()
    {
        $this->checkMethodType(__FUNCTION__, $_SERVER['REQUEST_METHOD']);
        $this->doCreate();
    }

    public abstract function doCreate();

    /**
     * POST Json, store
     * @return mixed
     */
    public final function store()
    {
        $this->checkMethodType(__FUNCTION__, $_SERVER['REQUEST_METHOD']);
        $this->doStore();
    }

    public abstract function doStore();

    /**
     * GET, /url/{item}
     * @return mixed
     */
    public final function show($id)
    {
        $this->checkMethodType(__FUNCTION__, $_SERVER['REQUEST_METHOD']);
        $this->doShow($id);
    }

    public abstract function doShow($id);

    /**
     * GET, /url/{item}/edit
     * @return mixed
     */
    public final function edit($id) {
        $this->checkMethodType(__FUNCTION__, $_SERVER['REQUEST_METHOD']);
        $this->doEdit($id);
    }

    public abstract function doEdit($id);

    /**
     * PUT/PATCH, /url/{item}
     * @return mixed
     */
    public final function update() {
        $this->checkMethodType(__FUNCTION__, $_SERVER['REQUEST_METHOD']);
        $this->doUpdate();
    }

    /**
     * DELETE, /url/{item}
     * @return mixed
     */
    public final function delete() {
        $this->checkMethodType(__FUNCTION__, $_SERVER['REQUEST_METHOD']);
        $this->doDelete();
    }

    public abstract function doDelete();

}
