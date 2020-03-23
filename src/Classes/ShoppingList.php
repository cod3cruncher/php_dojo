<?php

namespace PHPDojo\Classes;

class ShoppingList
{
    private $name;
    private $specialAutoloadingClass;

    public function __construct(string $name)
    {
        $this->setName($name);
        $this->specialAutoloadingClass = new SpecialClassToTestAutoloading();
    }

    public function getItems()
    {
        return ['Obst', 'Fleisch', 'Klopapier'];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getSpecialClassToTestAutoloading() : SpecialClassToTestAutoloading {
        return $this->specialAutoloadingClass;
    }
}