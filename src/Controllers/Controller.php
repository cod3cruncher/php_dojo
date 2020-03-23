<?php
namespace PHPDojo\Controllers;

abstract class Controller {

    public abstract function index() : void;

    public abstract function isLoginNeeded() : bool;



}