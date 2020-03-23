<?php
namespace PHPDojo\Controllers;

use PHPDojo\Helpers\DatabaseConnection;

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
        echo "I will show you the truth! id: " . $id;
    }

    public function edit($id)
    {
        echo 'You want to edit ' . $id;
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function doDestroy()
    {
        // TODO: Implement destroy() method.
    }

    public function index() :void
    {
        $connection = DatabaseConnection::mysqli();

        echo "You called index :) welcome!";
    }

    public function isLoginNeeded(): bool {
        return false;
    }


}
