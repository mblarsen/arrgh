<?php

class ArrghFunctionTest extends PHPUnit_Framework_TestCase
{
    const simple_array = [1, 2, 3, 4, 5];
    const simple_assoc_array = [
        "banana" => 1,
        "lemon"  => 2,
        "zealot" => 42
    ];
    
    public function before()
    {
        new Arrgh();
    }

    public function testMap()
    {
        $map_function = function ($item) { return -1 * $item; };

        $simple_array = self::simple_array;
        $native_result = array_map($map_function, $simple_array);

        $simple_array = self::simple_array;
        $arrgh_result = arrgh_map($simple_array, $map_function);

        $this->assertNotEmpty($native_result);
        $this->assertNotEmpty($arrgh_result);
        $this->assertEquals($native_result, $arrgh_result);
    }

    /* Tests the case of multiple arrays */
    public function testMapAssoc()
    {
        $map_function = function ($k, $v) { return $v *= $k === "banana" ? 4 : 1; };

        $simple_assoc_array = self::simple_assoc_array;
        $keys = array_keys($simple_assoc_array);
        $native_result = array_map($map_function, $keys, $simple_assoc_array);

        $simple_assoc_array = self::simple_assoc_array;
        $keys = array_keys($simple_assoc_array);
        $arrgh_result = arrgh_map(array_keys($simple_assoc_array), $simple_assoc_array, $map_function);

        $this->assertNotEmpty($native_result);
        $this->assertEquals(4, $native_result[0]);
        $this->assertNotEmpty($arrgh_result);
        $this->assertEquals(4, $arrgh_result[0]);
        $this->assertEquals($native_result, $arrgh_result);
    }

    /* A more practical example that mimics mapping of an associative array */
    public function testMapAssoc2()
    {
        $map_function = function ($k, $v) { return $v *= $k === "banana" ? 4 : 1; };

        $simple_assoc_array = self::simple_assoc_array;
        $keys = array_keys($simple_assoc_array);
        $native_result = array_combine($keys, array_map($map_function, $keys, $simple_assoc_array));

        $simple_assoc_array = self::simple_assoc_array;
        $keys = array_keys($simple_assoc_array);
        $arrgh_result = array_combine($keys, arrgh_map(array_keys($simple_assoc_array), $simple_assoc_array, $map_function));

        $this->assertNotEmpty($native_result);
        $this->assertEquals(4, $native_result["banana"]);
        $this->assertNotEmpty($arrgh_result);
        $this->assertEquals(4, $arrgh_result["banana"]);
        $this->assertEquals($native_result, $arrgh_result);
    }
    
    /* Tests associative mapping */
    public function testMapAss()
    {
        $map_function = function ($k, $v) { return $v *= $k === "banana" ? 4 : 1; };

        $simple_assoc_array = self::simple_assoc_array;
        $keys = array_keys($simple_assoc_array);
        $native_result = array_combine($keys, array_map($map_function, $keys, $simple_assoc_array));

        $simple_assoc_array = self::simple_assoc_array;
        $keys = array_keys($simple_assoc_array);
        $arrgh_result = arrgh_map_ass($simple_assoc_array, $map_function);

        $this->assertNotEmpty($native_result);
        $this->assertEquals(4, $native_result["banana"]);
        $this->assertNotEmpty($arrgh_result);
        $this->assertEquals(4, $arrgh_result["banana"]);
        $this->assertEquals($native_result, $arrgh_result);
    }
}
