<?php

class ArrghStaticTest extends PHPUnit_Framework_TestCase
{
    const simple_array = [1, 2, 3, 4, 5];
    const simple_array_negative = [-1, -2, -3 ,-4, -5];
    const simple_assoc_array = [
        "banana" => 1,
        "lemon"  => 2,
        "zealot" => 42
    ];

    public function testMap()
    {
        $map_function = function ($item) { return -1 * $item; };

        $simple_array = self::simple_array;
        $native_result = array_map($map_function, $simple_array);

        $simple_array = self::simple_array;
        $arrgh_result = Arrgh::map($simple_array, $map_function);

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
        $arrgh_result = Arrgh::map(array_keys($simple_assoc_array), $simple_assoc_array, $map_function);

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
        $arrgh_result = array_combine($keys, Arrgh::map(array_keys($simple_assoc_array), $simple_assoc_array, $map_function));

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
        $arrgh_result = Arrgh::mapAss($simple_assoc_array, $map_function);

        $this->assertNotEmpty($native_result);
        $this->assertEquals(4, $native_result["banana"]);
        $this->assertNotEmpty($arrgh_result);
        $this->assertEquals(4, $arrgh_result["banana"]);
        $this->assertEquals($native_result, $arrgh_result);
    }

    public function testCamelCaseNaming()
    {
        $map_function = function ($item) { return -1 * $item; };

        $simple_array = self::simple_array;
        $arrgh_result = Arrgh::arrayMap($simple_array, $map_function);

        $this->assertEquals(self::simple_array_negative, $arrgh_result);
    }

    public function testSortBy()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ];

        $sorted_by_name = Arrgh::sortBy($array, "name");
        $this->assertEquals([
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
        ], $sorted_by_name);

        $sorted_by_age = Arrgh::sortBy($array, "age");
        $this->assertEquals([
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ], $sorted_by_age);

        $sorted_by_zip_code_DESC = Arrgh::sortBy($array, "zip_code", "DESC");
        $this->assertEquals([
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
        ], $sorted_by_zip_code_DESC);

        $sorted_by_foo = Arrgh::sortBy($array, "foo");
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ], $sorted_by_foo);

        $array2 = $array;
        $array2[] = [ "name" => "Robocop", "age" => 99, "zip_code" => 9220, "foo" => "alabaster" ];
        $sorted_by_foo2 = Arrgh::sortBy($array2, "foo");
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Robocop", "age" => 99, "zip_code" => 9220, "foo" => "alabaster" ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ], $sorted_by_foo2);

        $sorted_by_bar = Arrgh::sortBy($array, "bar");
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ], $sorted_by_bar);
    }

    public function testSortByUserFunction()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ];

        $sorted_by_age_mod2 = Arrgh::sortBy($array, function ($a, $b) {
            return ($a["age"] % 2) - ($b["age"] % 2);
        });
        $this->assertEquals([
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
        ], $sorted_by_age_mod2);

        $sorted_by_zip_code_minus_age = Arrgh::sortBy($array, function ($a, $b) {
            return (floor($a["zip_code"]/100) - $a["age"] * 2) - (floor($b["zip_code"]/100) - $b["age"] * 2);
        });
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
        ], $sorted_by_zip_code_minus_age);

    }

    public function testCollapse()
    {
        $array = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], Arrgh::collapse($array));

        $array = [[1, 2, 3], [4, 5, 6], [7, 8, 9], 10];
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Arrgh::collapse($array));
    }

    public function testContains()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ];

        $this->assertTrue(Arrgh::contains($array, 2100));
        $this->assertFalse(Arrgh::contains($array, 2100, "age"));
        $this->assertFalse(Arrgh::contains($array, 666));

        $this->assertTrue(Arrgh::contains($array, "Jakob"));
        $this->assertFalse(Arrgh::contains($array, "Benji"));
    }

    public function testExcept()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ];

        $item = [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ];

        // Remove key-string
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 43, "foo" => "bar" ],
        ], Arrgh::except($array, "zip_code"));

        // Remove key-array single
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 43, "foo" => "bar" ],
        ], Arrgh::except($array, ["zip_code"]));

        // Remove key non-existent
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ], Arrgh::except($array, "foobar"));

        // Remove key-array multiple
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 43 ],
        ], Arrgh::except($array, ["zip_code", "foo"]));

        $this->assertEquals(
            [ "name" => "Jakob" ],
            Arrgh::except($item, ["zip_code", "age" ])
        );
    }

    public function testOnly()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ];

        $item = [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ];

        // Remove key-string
        $this->assertEquals([
            [ "zip_code" => 2100 ],
            [ "zip_code" => 6301 ],
            [ "zip_code" => 9210 ],
        ], Arrgh::only($array, "zip_code"));

        // Remove key-array single
        $this->assertEquals([
            [ "name" => "Jakob" ],
            [ "name" => "Topher" ],
            [ "name" => "Ginger" ],
        ], Arrgh::only($array, ["name"]));

        // Remove key non-existent
        $this->assertEquals([
            [ ],
            [ ],
            [ ],
        ], Arrgh::only($array, "foobar"));

        // Remove key-array multiple
        $this->assertEquals([
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 43 ],
        ], Arrgh::only($array, ["name", "age"]));

        $this->assertEquals(
            [ "name" => "Jakob" ],
            Arrgh::only($item, ["name" ])
        );
    }

    public function testDotGet()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "children" => [
                [ "name" => "Mona", "sex" => "female" ],
                [ "name" => "Lisa", "sex" => "female" ],
            ] ],
            [ "name" => "Topher", "age" => 18, "children" => [
                [ "name" => "Joe", "sex" => "male" ],
            ] ],
            [ "name" => "Ginger", "age" => 43 ],
        ];

        $this->assertEquals([ "name" => "Ginger", "age" => 43 ], Arrgh::dotGet($array, "2"));
        $this->assertEquals("Ginger", Arrgh::dotGet($array, "2.name"));
        $this->assertEquals([ "Jakob", "Topher", "Ginger" ], Arrgh::dotGet($array, "name"));
        $this->assertEquals(
            [ [ "Mona", "Lisa" ], [ "Joe" ] ],
            Arrgh::dotGet($array, "children.name")
        );

        $this->assertEquals(
            [ "Mona", "Lisa", "Joe" ],
            Arrgh::dotGet($array, "children.name", $collapse = true)
        );

        $this->assertEquals(
            [ "Mona", "Joe" ],
            Arrgh::dotGet($array, "children.0.name", $collapse = true)
        );

        $this->assertEquals(
            [ "Lisa" ],
            Arrgh::dotGet($array, "children.1.name", $collapse = true)
        );

        $this->assertEquals(
            [ ],
            Arrgh::dotGet($array, "children.2.name", $collapse = true)
        );

        $this->assertEquals(
            [ "Lisa", "Joe" ],
            Arrgh::dotGet($array, "children.!>.name", $collapse = true)
        );

        $this->assertEquals( [null, null, null], Arrgh::dotGet($array, "dad"));
        $this->assertEquals( [ ], Arrgh::dotGet($array, "dad", $collapse = true));

        $this->assertEquals(
            [ "Mona", "Lisa" ],
            Arrgh::dotGet(
                $array,
                [
                    "children.!$.name",
                    function ($item, $index) {
                        return $item['sex'] === 'female';
                    }
                ]
            )
        );

        // Return non-child bearing over age 35
        $this->assertEquals(
            [ [ "name" => "Ginger", "age" => 43 ] ],
            Arrgh::dotGet(
                $array,
                [
                    "!$",
                    function ($item, $index) {
                        // var_dump($item);
                        return !isset($item["children"]) && $item["age"] > 35;
                    }
                ]
            )
        );

        // $this->assertEquals(
        //     [ [ "name" => "Mona", "sex" => "female" ] ],
        //     Arrgh::dotGet($array, "0.children.0")
        // );
    }
}
