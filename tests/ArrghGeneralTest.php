<?php

use Arrgh\Arrgh;

class ArrghGeneralTest extends PHPUnit_Framework_TestCase
{
    public function testMethodNames()
    {
        $input           = [1, 2, 3, 4, 5];
        $expected_output = [-1, -2, -3 ,-4, -5];
        $map_function    = function ($item) { return -1 * $item; };

        $this->assertEquals($expected_output, Arrgh::array_map($input, $map_function));
        $this->assertEquals($expected_output, Arrgh::arrayMap($input, $map_function));
        $this->assertEquals($expected_output, Arrgh::map($input, $map_function));

        $this->assertEquals($expected_output, Arrgh::_array_map($input, $map_function)->toArray());
        $this->assertEquals($expected_output, Arrgh::_arrayMap($input, $map_function)->toArray());
        $this->assertEquals($expected_output, Arrgh::_map($input, $map_function)->toArray());
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
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
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

    public function testGet()
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

        $this->assertEquals([ "name" => "Ginger", "age" => 43 ], Arrgh::get($array, 2));
        $this->assertEquals([ "name" => "Ginger", "age" => 43 ], Arrgh::get($array, "2"));
        $this->assertEquals("Jakob", Arrgh::get($array[0], "name"));
        $this->assertEquals("Ginger", Arrgh::get($array, "2.name"));
        $this->assertEquals([ "Jakob", "Topher", "Ginger" ], Arrgh::get($array, "name"));

        $this->assertEquals([ [ "Mona", "Lisa" ], [ "Joe" ] ], Arrgh::get($array, "children.name"));
        $this->assertEquals([ "Mona", "Lisa", "Joe" ], Arrgh::get($array, "children.name", $collapse = true));

        $this->assertEquals( [ "Mona", "Joe" ], Arrgh::get($array, "children.0.name", $collapse = true) );
        $this->assertEquals( [ ["Mona"], ["Joe"] ], Arrgh::get($array, "children.0.name") );

        $this->assertEquals( [ "Lisa" ], Arrgh::get($array, "children.1.name", $collapse = true) );

        $this->assertEquals( [ ], Arrgh::get($array, "children.2.name", $collapse = true) );

        $this->assertEquals( [ "Lisa", "Joe" ], Arrgh::get($array, "children.-1.name", $collapse = true) );
        $this->assertEquals( [ ["Lisa"], ["Joe"] ], Arrgh::get($array, "children.-1.name") );

        $this->assertEquals( [null, null, null], Arrgh::get($array, "dad"));
        $this->assertEquals( [ ], Arrgh::get($array, "dad", $collapse = true));

        $this->assertEquals(
            [ "Mona", "Lisa" ],
            Arrgh::get(
                $array,
                [
                    "children.!$.name",
                    function ($item, $index) {
                        return $item['sex'] === 'female';
                    }
                ],
                $collapse = true
            )
        );

        // Return non-child bearing over age 35
        $this->assertEquals(
            [ [ "name" => "Ginger", "age" => 43 ] ],
            Arrgh::get(
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

        $this->assertEquals(
            [ "name" => "Mona", "sex" => "female" ],
            Arrgh::get($array, "0.children.0")
        );

        $this->assertEquals(
            "Mona",
            Arrgh::get($array, "0.children.0.name")
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetPathNotInArray()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "children" => [
                [ "name" => "Mona", "sex" => "female" ],
                [ "name" => "Lisa", "sex" => "female" ],
            ] ],
            [ "name" => "Topher", "age" => 18, "children" => [
                [ "name" => "Joe", "sex" => "male" ],
            ] ],
        ];

        $this->assertEmpty(Arrgh::get($array, "children.toys.name"));

        $array = [
            [ "name" => "Jakob", "age" => 37, "children" => [
                [ "name" => "Mona", "sex" => "female" ],
                [ "name" => "Lisa", "sex" => "female" ],
            ] ],
            [ "name" => "Topher", "age" => 18, "children" => 7],
        ];

        $this->assertEquals([], Arrgh::get($array, "children.toys"));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidMethod()
    {
        Arrgh::foobar([42]);
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidFunctionPath()
    {
        $array = [
            [ "name" => "Jakob", "age" => 37, "zip_code" => 2100 ],
            [ "name" => "Topher", "age" => 18, "zip_code" => 6301 ],
            [ "name" => "Ginger", "age" => 43, "zip_code" => 9210, "foo" => "bar" ],
        ];

        $this->assertEquals(["Jakob"],
            Arrgh::get(
                $array,
                [
                    "!$.name",
                    function ($item, $index) { return $item["name"] === "Jakob"; }
                ]
            )
        );

        $this->assertEquals(["Jakob"],
            Arrgh::get(
                $array,
                [
                    "name.!$",
                    function ($item, $index) { return $item["name"] === "Jakob"; }
                ]
            )
        );
    }

    public function testPhpVersionFail56()
    {
        $php_version = explode(".", phpversion());

        $original_input = [
            [ "name" => "Jakob", "age" => 42 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 42 ],
        ];
        $expected_result = [
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Jakob", "age" => 42 ],
            [ "name" => "Ginger", "age" => 42 ],
        ];

        $input = $original_input;
        usort($input, function ($a, $b) {
            return $a["age"] - $b["age"];
        });

        if ($php_version[0] >= 7) {
            $this->assertEquals($expected_result, $input);
        } else {
            $this->assertNotEquals($expected_result, $input);
        }
    }

    public function testPhpVersionFail7()
    {
        $php_version = explode(".", phpversion());

        $original_input = [
            [ "name" => "Jakob", "age" => 42 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 42 ],
        ];
        $expected_result = [
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 42 ],
            [ "name" => "Jakob", "age" => 42 ],
        ];

        $input = $original_input;
        usort($input, function ($a, $b) {
            return $a["age"] - $b["age"];
        });

        if ($php_version[0] >= 7) {
            $this->assertNotEquals($expected_result, $input);
        } else {
            $this->assertEquals($expected_result, $input);
        }
    }

    public function testPhpVersion()
    {
        $original_input = [
            [ "name" => "Jakob", "age" => 42 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 42 ],
        ];
        $expected_result = [
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Jakob", "age" => 42 ],
            [ "name" => "Ginger", "age" => 42 ],
        ];

        // Shows how getSortDirection can return the proper sort direction
        $input = $original_input;
        usort($input, function ($a, $b) {
            return Arrgh::getSortDirection($a["age"] - $b["age"]);
        });

        // Shows that usort callable works without it when using _Arrgh_ methods
        $input = $original_input;
        $this->assertEquals($expected_result, Arrgh::usort($input, function ($a, $b) {
            return $a["age"] - $b["age"];
        }));
    }

    public function testMultisortChained()
    {
        $input1 = [100, 1, 10, 1000];
        $input2 = [1, 3, 2, 4];
        $this->assertEquals(3, Arrgh::chain($input1)->multisort($input2)->pop()->shift());
    }

    public function testMultisortWithColumn()
    {
        $input = [
            [ "id" => "42", "name" => "Jakob", "age" => 37 ],
            [ "id" => "43", "name" => "Topher", "age" => 18 ],
            [ "id" => "43", "name" => "Allan" ],
        ];

        $this->assertEquals([ 37, 18 ], array_column($input, "age"));
        $this->assertEquals([ 37, 18, null ], arr_column($input, "age"));
        $this->assertEquals([
            [ "id" => "43", "name" => "Allan" ],
            [ "id" => "43", "name" => "Topher", "age" => 18 ],
            [ "id" => "42", "name" => "Jakob", "age" => 37 ],
        ], arr_multisort(arr_column($input, "age"), $input)[1]);
    }

    public function testArrghOfArrgs()
    {
        $arr = arr([arr([5, 4]), arr(["a", "b"])]);
        $this->assertEquals([[5,4], ["a", "b"]], $arr->toArray());
    }
}
