<?php
require __DIR__ . "/vendor/autoload.php";

use PHPDojo\Classes\Route;
use PHPDojo\Controllers\IndexController;
use PHPDojo\Controllers\TestController;
use PHPDojo\Controllers\TodoListController;
use PHPDojo\Classes\ControllerContainer;
use PHPDojo\Helpers\DatabaseConnection;
use PHPDojo\Helpers\TableCreator;
use PHPDojo\Models\User;
use PHPDojo\Models\TodoList;

session_start();

Route::add('/', 'LoginController@index');
Route::add('/login', 'LoginController@login', 'post');
Route::add('/logout', 'LoginController@logout');
Route::resource('/todolist', 'TodoListController');

Route::run('/');
