<?php
require __DIR__ . "/vendor/autoload.php";
use PHPDojo\Helpers\TemplateRenderer;

$html = TemplateRenderer::render(
    'SimpleSampleView.simptemp',
    [
        'title' => "MyFancyTitle",
        'message' => 'My Top-Secret Message!',
        'content' => '<script> alert("Hello! I am an alert box!!"); </script>'
    ], false);
echo $html;