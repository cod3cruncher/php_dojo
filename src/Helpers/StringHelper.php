<?php
namespace PHPDojo\Helpers;

class StringHelper {

    private function __construct() {}

    public static function endsWith($toTest, $end, $isCaseSensitive = true) {
        if(!$isCaseSensitive) {
            $toTest = strtolower($toTest);
            $end = strtolower($end);
        }

        if(strlen($end) == 0) {
            return true;
        }
        if(strlen($toTest) < strlen($end)) {
            return false;
        }
        $offset = strlen($toTest) - strlen($end);
        return (boolean)strpos($toTest, $end, $offset);
    }
}