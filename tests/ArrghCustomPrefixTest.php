<?php

namespace Arrgh;

define("ARRGH_REDEFINE", true);
define("ARRGH_PREFIX", "loyo");

require dirname(__FILE__) . "/../src/arrgh_functions.php";

class ArrghCustomPrefixTest extends \PHPUnit_Framework_TestCase
{
    public function testCustomPrefix()
    {
        $arrgh = loyo(["a", "b", "c"]);
        $this->assertEquals(["a", "b", "c"], $arrgh->toArray());
        $this->assertEquals(["aa", "bb", "cc"], loyo_map($arrgh, function ($item) { return $item . $item; }));
    }
}
