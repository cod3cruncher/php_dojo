<?php
namespace PHPDojo\Classes;

class SpecialClassToTestAutoloading {

    private $name;

    public function __construct()
    {
        $this->name = "specialClassToTestAutoloading";
    }

    public function getName() {
        return $this->name;
    }
}