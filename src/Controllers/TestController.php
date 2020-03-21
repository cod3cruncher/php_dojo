<?php
namespace PHPDojo\Controllers;

class TestController extends CrudController {

    public function doCreate()
    {
        // TODO: Implement doCreate() method.
    }

    public function doStore()
    {
        // TODO: Implement doStore() method.
    }

    public function doShow($id)
    {
        echo "I will show you the truth! " . $id;
    }

    public function edit($id)
    {
        echo 'You want to edit ' . $id;
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }

    public function index()
    {
        echo "You called index :) welcome!";
    }


}
