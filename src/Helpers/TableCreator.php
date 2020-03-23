<?php

namespace PHPDojo\Helpers;

class TableCreator
{



    public static function createTodoTable() {


        $mysli = DatabaseConnection::mysqli();
        $mysli->query($sql);

    }

    public static function createTodoItemsTable() {
        $sql =

        $mysqli = DatabaseConnection::mysqli();
        $mysqli->query($sql);
    }
}
