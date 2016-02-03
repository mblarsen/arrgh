<?php

define("ARRGH_REDEFINE", true);
define("ARRGH_PREFIX", "arr");

require dirname(__FILE__) . "/../src/arrgh_functions.php";

class ArrghCustomPrefixTest extends PHPUnit_Framework_TestCase
{
    public function testCustomPrefix()
    {
        $arrgh = arr(["a", "b", "c"]);
        $this->assertEquals(["a", "b", "c"], $arrgh->toArray());
        
        $this->assertEquals(["aa", "bb", "cc"], arr_map($arrgh, function ($item) { return $item . $item; }));
    }
}
