<?php
require __DIR__ . "/vendor/autoload.php";
//use PHPDojo\Helpers\TemplateRenderer;
//
//$html = TemplateRenderer::render(
//    'SimpleSampleView.simptemp',
//    [
//        'title' => "MyFancyTitle",
//        'message' => 'My Top-Secret Message!',
//        'content' => '<script> alert("Hello! I am an alert box!!"); </script>'
//    ], false);
//echo $html;

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

//$todoItemNew = new \PHPDojo\Models\TodoListItem();
//$todoItemNew->setTitle('Mein hyperneues neues Element');
//$todoListNew = TodoList::find(8);
//$todoListNew->addItem($todoItemNew);
//$todoListNew->save();


//$user = new User();
////$user->setId(7);
//$user->setName('Patrick');
//$user->setPassword('abc');
//$user->save();

//User::createTable();
//TodoList::createTable();
//\PHPDojo\Models\TodoListItem::createTable();


//$todoLists = TodoList::all();
//$todoLists = TodoList::allForUser(User::find(1));
//print_r($todoLists);
//print_r(User::all());

Route::add('/', 'LoginController@index');
Route::add('/login', 'LoginController@login', 'post');
Route::add('/logout', 'LoginController@logout');
Route::resource('/todolist', 'TodoListController');




//var_dump(User::find(7));

//User::getTableName();

// Add base route (startpage)
//Route::add('/',function(){
//    echo IndexController::index();
//});

// Add base route (startpage)
//Route::add('/test','TestController@show');

//Route::add('/', 'IndexController@index');
//
//Route::resource('/test','TestController');





//// Simple test route that simulates static html file
//Route::add('/test.html',function(){
//    echo 'Hello from test.html';
//});
//
//// Post route example
//Route::add('/contact-form',function(){
//    echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
//},'get');
//
//// Post route example
//Route::add('/contact-form',function(){
//
//    //Make sure that the content type of the POST request has been set to application/json
//    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
//    if(strcasecmp($contentType, 'application/json') != 0){
//        throw new Exception('Content type must be: application/json');
//    }
////    echo 'Hey! The form has been sent:<br/>';
//    //Receive the RAW post data.
//    $content = trim(file_get_contents("php://input"));
////    print_r($content);
//
////Attempt to decode the incoming RAW post data from JSON.
//    $decoded = json_decode($content, true);
////     print_r($decoded);
//     echo $decoded['title'];
//},'post');
//
//// Accept only numbers as parameter. Other characters will result in a 404 error
//Route::add('/foo/([0-9]*)/bar',function($var1){
//    echo $var1.' is a great number!';
//});

Route::run('/');
