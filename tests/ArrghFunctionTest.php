<?php

class ArrghFunctionTest extends PHPUnit_Framework_TestCase
{
    private $string_compare_function;
    private $number_compare_function;

    public function setup()
    {
        $this->string_compare_function = function ($a, $b) { 
            $result = $a - $b;
            if ($result === 0) {
                return Arrgh::getPhpSortDirection();
            }
            return $result;
        };
        $this->number_compare_function = function ($a, $b) {
            $result = strcasecmp($a, $b);
            if ($result === 0) {
                return Arrgh::getPhpSortDirection();
            }
            return $result;
        };
    }

    public function testArrgh()
    {
        $this->assertTrue(arrgh() instanceof Arrgh);
    }

    public function testMapAss()
    {
        $input = [ "banana" => "fruit", "cycle" => "vehicle", "apple" => "fruit" ];
        $map_function = function ($key, $value) {
            if ($value === "fruit") {
                return "loom";
            }
            return $value;
        };

        $output = arrgh_map_ass($input, $map_function);
        $this->assertEquals([ "loom", "vehicle", "loom"], array_values($output));
    }

    public function testSortBy()
    {
        $input = [
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 43 ],
        ];

        $output = arrgh_sort_by($input, "name");
        $this->assertEquals([
            [ "name" => "Ginger", "age" => 43 ],
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
        ], $output);
    }

    function testCollapse()
    {
        $input = [[1, 2], [3, 4], 4, 4, null, 5];
        $output = arrgh_collapse($input);
        $this->assertEquals([1, 2, 3, 4, 4, 4, null, 5], $output);
    }

    function testContains()
    {
        $input = [
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 43 ],
        ];

        $this->assertTrue(arrgh_contains($input, "Ginger"));
        $this->assertFalse(arrgh_contains($input, "Foobar"));
        $this->assertFalse(arrgh_contains($input, "Ginger", "age"));
    }

    function testExcept()
    {
        $input = [
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
        ];

        $output = arrgh_except($input, "age");
        $this->assertEquals([
            [ "name" => "Jakob" ],
            [ "name" => "Topher" ],
        ], $output);
    }

    function testOnly()
    {
        $input = [
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
        ];

        $output = arrgh_only($input, "name");
        $this->assertEquals([
            [ "name" => "Jakob" ],
            [ "name" => "Topher" ],
        ], $output);
    }

    function testGet()
    {
        $input = [
            [ "name" => "Jakob", "age" => 37 ],
            [ "name" => "Topher", "age" => 18 ],
        ];

        $this->assertEquals("Topher",  arrgh_get($input, "1.name"));
    }

    function testIsCollection()
    {
        $input = [];
        $input2 = [ 0, 1, 2, 3 ];
        $input3 = [ "name" => "Jakob", "age" => 37 ];
        $input4 = [ [ "name" => "Jakob", "age" => 37 ] ];

        $tests = [
            [$input, false],
            [$input2, true],
            [$input3, false],
            [$input4, true],
        ];

        foreach ($tests as $test_pair) {
            list($test, $expected) = $test_pair;
            $this->assertEquals($expected, arrgh_is_collection($test));
        }
    }

    function testDepth()
    {
        $input = [];
        $input2 = [ 0, 1, 2, 3 ];
        $input3 = [ "name" => "Jakob", "age" => 37 ];
        $input4 = [ [ "name" => "Jakob", "age" => 37 ] ];
        $input5 = [ [ [ "name" => "Jakob", "age" => 37 ] ] ];

        $tests = [
            [$input, 0],
            [$input2, 1],
            [$input3, 0],
            [$input4, 1],
            [$input5, 2],
        ];

        foreach ($tests as $test_pair) {
            list($test, $expected) = $test_pair;
            $this->assertEquals($expected, arrgh_depth($test));
        }
    }

    public function testChangeKeyCase()
    {
        $input = [ "name" => "Jakob", "age" => 37 ];
        $output = arrgh_change_key_case($input, CASE_UPPER);
        $this->assertEquals([ "NAME", "AGE"], array_keys($output));
    }

    public function testChunk()
    {
        $input = [1, 2, 3, 4, 5];
        $this->assertEquals([[1, 2], [3, 4], [5]], arrgh_chunk($input, 2));
    }

    public function testColumn()
    {
        $input = [
            [ "id" => "42", "name" => "Jakob", "age" => 37 ],
            [ "id" => "43", "name" => "Topher", "age" => 18 ],
        ];

        $this->assertEquals([ "Jakob", "Topher" ], arrgh_column($input, "name"));
        $this->assertEquals([ 42 => "Jakob", 43 =>"Topher" ], arrgh_column($input, "name", "id"));
    }

    public function testCombine()
    {
        $input_keys = [ "a", "b", "c" ];
        $input_vaules = [ 1, 2, 3 ];
        $this->assertEquals([ "a" => 1, "b" => 2, "c" => 3], arrgh_combine($input_keys, $input_vaules));
    }

    public function testCountValues()
    {
        $input = [ 1, 2, 2, 3, 4];
        $this->assertEquals([ 1 => 1, 2 => 2, 3 => 1, 4 => 1], arrgh_count_values($input));
    }

    public function testDiff()
    {
        $input1 = [ "a", "b", "c" ];
        $input2 = [ "b", "c", "d" ];
        $this->assertEquals(["a"], arrgh_diff($input1, $input2));
    }

    public function testDiffAssoc()
    {
        $input1 = [ "a" => 2, "b" => 4, "c" => 8 ];
        $input2 = [ "a" => 1, "b" => 4, "c" => 8 ];
        $this->assertEquals(["a" => 2], arrgh_diff_assoc($input1, $input2));
    }

    public function testDiffKey()
    {
        $input1 = [ "a" => 2, "b" => 4, "c" => 8 ];
        $input2 = [ "b" => 2, "c" => 4, "d" => 8 ];
        $this->assertEquals(["a" => 2], arrgh_diff_key($input1, $input2));
    }

    public function testDiffUassoc()
    {
        $input1 = [ "a" => 2, "b" => 4, "c" => 8 ];
        $input2 = [ "b" => 2, "c" => 4, "d" => 8 ];
        $this->assertEquals(["a" => 2, "b" => 4, "c" => 8], arrgh_diff_uassoc($input1, $input2, $this->number_compare_function));
    }

    public function testDiffUkey()
    {
        $input1 = [ "a" => 2, "b" => 4, "c" => 8 ];
        $input2 = [ "b" => 2, "c" => 4, "d" => 8 ];
        $compare_function = function ($a, $b) { return strcasecmp($a, $b); };
        $this->assertEquals(["a" => 2], arrgh_diff_ukey($input1, $input2, $compare_function));
    }

    public function testFill()
    {
        $this->assertEquals(["", "", ""], arrgh_fill(0, 3, ""));
    }

    public function testFillKeys()
    {
        $this->assertEquals([ "a" => 1, "b" => 1], arrgh_fill_keys(["a", "b"], 1));
    }

    public function testFilter()
    {
        $input = [ 1, 2, 3 ];
        $this->assertEquals([2 => 3], arrgh_filter($input, function ($element) { return $element > 2; }));
    }

    public function testFlip()
    {
        $input = [ 1, 2, 3 ];
        $this->assertEquals([ 1 => 0, 2 => 1, 3 => 2], arrgh_flip($input));
    }

    public function testIntersect()
    {
        $input1 = [ 1, 2, 3 ];
        $input2 = [ 3, 4, 5 ];
        $this->assertEquals([2 => 3], arrgh_intersect($input1, $input2));
    }

    public function testIntersectAssoc()
    {
        $input1 = [ "currency" => "DKK", "live" => true];
        $input2 = [ "currency" => "DKK" ];
        $input3 = [ "country" => "DKK" ];
        $this->assertEquals(["currency" => "DKK"], arrgh_intersect_assoc($input1, $input2));
        $this->assertEquals([], arrgh_intersect_assoc($input1, $input3));
    }

    public function testIntersectKey()
    {
        $input1 = [ "currency" => "DKK", "live" => true];
        $input2 = [ "currency" => "USD" ];
        $this->assertEquals(["currency" => "DKK"], arrgh_intersect_key($input1, $input2));
    }

    /*
    arrgh_intersect_uassoc
    arrgh_intersect_ukey
    */

    public function testKeys()
    {
        $input = [ 1, 2, 3 ];
        $this->assertEquals([0, 1, 2], arrgh_keys($input));
    }

    public function testMerge()
    {
        $input1 = [1, 2, 3];
        $input2 = [4, 5, 6];
        $this->assertEquals([1, 2, 3, 4, 5, 6], arrgh_merge($input1, $input2));
    }

    /*
    arrgh_merge_recursive
    */

    public function testPad()
    {
        $input = [ 1, 2, 3 ];
        $this->assertEquals([1, 2, 3, 0, 0], arrgh_pad($input, 5, 0));
    }

    public function testProduct()
    {
        $input = [ 1, 2, 3 ];
        $this->assertEquals(6, arrgh_product($input));
    }

    public function testRand()
    {
        $input = [ "a", "b", "c" ];
        $random = array_rand($input);
        $this->assertTrue(in_array($random, array_keys($input)));
    }

    public function testReduce()
    {
        $input = [1, 2, 3];
        $reduce_function = function ($carry, $item) { return $carry *= $item * 2; };
        $this->assertEquals(48, arrgh_reduce($input, $reduce_function, 1));
    }

    public function testReplace()
    {
        $default_settings = [ "currency" => "USD", "live" => false ];
        $settings = [ "currency" => "DKK" ];
        $this->assertEquals([ "currency" => "DKK", "live" => false ], arrgh_replace($default_settings, $settings));
    }

    /*
    arrgh_replace_recursive
    */

    public function testReverse()
    {
        $input = [1, 2, 3];
        $this->assertEquals([3, 2, 1], arrgh_reverse($input));
    }

    public function testSlice()
    {
        $input = [1, 2, 3, 4];
        $this->assertEquals([2, 3], arrgh_slice($input, 1, 2));
    }

    public function testSum()
    {
        $input = [1, 2, 3];
        $this->assertEquals(6, arrgh_sum($input));
    }

    /*
    arrgh_udiff
    arrgh_udiff_assoc
    arrgh_udiff_uassoc
    arrgh_uintersect
    arrgh_uintersect_assoc
    arrgh_uintersect_uassoc
    */

    public function testUnique()
    {
        $input = [1, 2, 3, 3, 4, 1, 5];
        $this->assertEquals([1, 2, 3, 4, 5], array_values(arrgh_unique($input)));
    }

    public function testValues()
    {
        $input = [1, 2, 3];
        $this->assertEquals([1, 2, 3], arrgh_values($input));
    }

    public function testCount()
    {
        $input = [1, 2, 3];
        $this->assertEquals(3, arrgh_count($input));
    }

    public function testMin()
    {
        $input = [1, 3, 2];
        $this->assertEquals(1, arrgh_min($input));
    }

    public function testMax()
    {
        $input = [1, 3, 2];
        $this->assertEquals(3, arrgh_max($input));
    }

    public function testRange()
    {
        $this->assertEquals([0 => 2, 1 => 3], arrgh_range(2, 3));
    }

    public function testSizeof()
    {
        $input = [1, 2, 3];
        $this->assertEquals(3, arrgh_sizeof($input));
    }

    public function testMap()
    {
        $input = [1, 2, 3, 4, 5];
        $map_function = function ($item) { return -1 * $item; };
        $this->assertEquals([-1, -2, -3, -4, -5], arrgh_map($input, $map_function));
    }

    public function testKeyExists()
    {
        $input = [ "a" => null, "b" => 1000 ];
        $this->assertFalse(isset($input["a"]));
        $this->assertTrue(arrgh_key_exists($input, "a"));
    }

    /*
    arrgh_search
    */

    public function testImplode()
    {
        $input = [ "a", "b", "c"];
        $this->assertEquals("a,b,c", arrgh_implode($input, ","));
    }

    public function testInArray()
    {
        $input = [ "a", "b", "c" ];
        $this->assertTrue(arrgh_in_array($input, "a"));
        $this->assertFalse(arrgh_in_array($input, "z"));
    }

    public function testJoin()
    {
        $input = [ "a", "b", "c"];
        $this->assertEquals("a,b,c", arrgh_join($input, ","));

    }

    /*
    arrgh_multisort
    */

    public function testPush()
    {
        $input = [ "a" ];
        $this->assertEquals([ "a", "b" ], arrgh_push($input, "b"));
    }

    public function testSplice()
    {
        $input = [ "a", "foo", "d" ];
        $this->assertEquals([ "a", "b", "c", "d"], arrgh_splice($input, 1, 1, ["b", "c"]));
    }

    public function testWalk()
    {
        $input = [ "a", "b", "c" ];
        $sum = 0;
        $output = arrgh_walk($input, function (&$item, $key, $prefix) use (&$sum) {
            $item = $prefix . $item;
            $sum += $key;
        }, "|");
        $this->assertEquals([ "|a", "|b", "|c"], $output);
        $this->assertEquals(3, $sum);
    }

    /*
    arrgh_walk_recursive
    arrgh_arsort
    arrgh_asort
    arrgh_krsort
    arrgh_ksort
    arrgh_natcasesort
    arrgh_natsort
    arrgh_rsort
    */

    public function testShuffle()
    {
        $input = range("a", "z");
        $output = arrgh_shuffle($input);
        $this->assertEquals(count($input), count($output));
        $this->assertNotEquals($input, $output);
        foreach ($output as $item) {
            $this->assertTrue(in_array($item, $input));
        }
    }

    public function testSort()
    {
        $input = range("a", "z");
        $messed_up = array_reverse($input);
        $this->assertEquals("z", $messed_up[0]);
        $this->assertEquals($input, arrgh_sort($input));
    }

    /*
    arrgh_uasort
    arrgh_uksort
    arrgh_usort
    */

    public function testPop()
    {
        $input = [1, 2, 5];
        $this->assertEquals(5, arrgh_pop($input));
    }

    public function testEnd()
    {
        $input = [1, 2, 5];
        $this->assertEquals(5, arrgh_end($input));
    }
}
