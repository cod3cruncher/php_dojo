<?php
namespace PHPDojo\Helpers;

/**
 * Class TemplateRenderer
 * This is the template render 'engine'
 *
 * @package PHPDojo\Helpers
 */

class TemplateRenderer {

    public const ENDING = '.simptemp';

    private function __construct() {}

    public static function render($uniquethe_simple_template_name, $arguments=[], $escapeArgs = true) {
        if(!self::hasCorrectEnding($uniquethe_simple_template_name)) {
            throw new \Error("Only ." . self::ENDING . " template files could be rendered!");
        }
        if($uniquethe_simple_template_name)
        ob_start(); //activates the output buffer: script output is not sent to client, instead saved in buffer
        if(is_array($arguments)) {
            if($escapeArgs) {
                $arguments = self::doHtmlSpecialChars($arguments);
            }
            extract($arguments); //imports keys as variables with values
        }
        $uniquethe_simple_template_name = str_replace('.php', '', $uniquethe_simple_template_name);
        include(__DIR__ . '/../Views/' . $uniquethe_simple_template_name . '.php');
        return ob_get_clean();
    }

    private static function doHtmlSpecialChars(array $arguments) {
        foreach ($arguments as $key => $value) {
            $arguments[$key] = htmlspecialchars($value, ENT_QUOTES);
        }
        return $arguments;
    }

    private static function hasCorrectEnding($name) : bool {
        return StringHelper::endsWith($name, self::ENDING);
    }
}