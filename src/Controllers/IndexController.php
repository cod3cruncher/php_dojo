<?php

namespace PHPDojo\Controllers;

use PHPDojo\Helpers;

class IndexController extends Controller
{
    public function index() : void{
        echo Helpers\TemplateRenderer::render('SimpleSampleView.simptemp', [
                'title' => 'Eine neue krasse Page!',
                'message' => 'Das musst du dir reinziehn!',
                'content' => 'Also pass auf, jetzt kommts ....ähm....'
            ]
        );
    }
}
