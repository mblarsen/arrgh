<?php

class ArrghFunctionTest extends PHPUnit_Framework_TestCase
{
    private $string_compare_function;
    private $number_compare_function;

    public function setup()
    {
        $this->string_compare_function = function ($a, $b) {
            return strcasecmp($a, $b);
        };
        $this->number_compare_function = function ($a, $b) {
            return $a - $b;
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
        $this->assertEquals(["a" => 2, "b" => 4, "c" => 8], arrgh_diff_uassoc($input1, $input2, $this->string_compare_function));
    }

    public function testDiffUkey()
    {
        $input1 = [ "a" => 2, "b" => 4, "c" => 8 ];
        $input2 = [ "b" => 2, "c" => 4, "d" => 8 ];
        $compare_function = $this->string_compare_function;//function ($a, $b) { return strcasecmp($a, $b); };
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

    public function testIntersectUassoc()
    {
        $input1 = [ "currency" => "DKK", "live" => true];
        $input2 = [ "currency" => "DKK" ];
        $input3 = [ "country" => "DKK" ];
        $this->assertEquals(["currency" => "DKK"], arrgh_intersect_uassoc($input1, $input2, $this->string_compare_function));
        $this->assertEquals([], arrgh_intersect_uassoc($input1, $input3, $this->string_compare_function));
    }

    public function testIntersectUkey()
    {
        $input1 = [ "currency" => "DKK", "live" => true];
        $input2 = [ "currency" => "USD" ];
        $this->assertEquals(["currency" => "DKK"], arrgh_intersect_ukey($input1, $input2, $this->string_compare_function));
    }

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

    public function testMergeRecursive()
    {
        $default_settings = [ "currency" => "USD", "live" => false, "modules" => [
            "abc" => "off",
            "def" => "off",
        ] ];
        $settings = [ "currency" => "DKK", "modules" => [
            "def" => "on"
        ] ];
        $this->assertEquals([ "currency" => ["USD", "DKK"], "live" => false, "modules" => [
            "abc" => "off",
            "def" => ["off", "on"],
        ] ], arrgh_merge_recursive($default_settings, $settings));
    }

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

    // array_replace_recursive
    public function testReplaceRecursive()
    {
        $default_settings = [ "currency" => "USD", "live" => false, "modules" => [
            "abc" => "off",
            "def" => "off",
        ] ];
        $settings = [ "currency" => "DKK", "modules" => [
            "def" => "on"
        ] ];
        $this->assertEquals([ "currency" => "DKK", "live" => false, "modules" => [
            "abc" => "off",
            "def" => "on",
        ] ], arrgh_replace_recursive($default_settings, $settings));
    }

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

    public function testUdiff()
    {
        $input1 = [ "a", "b", "c" ];
        $input2 = [ "b", "c", "d" ];
        $this->assertEquals(["a"], arrgh_udiff($input1, $input2, $this->string_compare_function));
    }

    public function testUdiffAssoc()
    {
        $input1 = [ "a" => 2, "b" => 4, "c" => 8 ];
        $input2 = [ "a" => 1, "b" => 4, "c" => 8 ];
        $this->assertEquals(["a" => 2], arrgh_udiff_assoc($input1, $input2, $this->number_compare_function));
    }

    public function testUdiffUassoc()
    {
        $input1 = [ "a" => 2, "b" => 4, "c" => 8 ];
        $input2 = [ "a" => 1, "b" => 4, "c" => 8 ];
        $this->assertEquals(["a" => 2], arrgh_udiff_uassoc($input1, $input2, $this->number_compare_function, $this->string_compare_function));
    }

    public function testUintersect()
    {
        $input1 = [ 1, 2, 3 ];
        $input2 = [ 3, 4, 5 ];
        $this->assertEquals([2 => 3], arrgh_uintersect($input1, $input2, $this->number_compare_function));
    }

    public function testUintersectAssoc()
    {
        $input1 = [ "currency" => "DKK", "live" => true];
        $input2 = [ "currency" => "DKK" ];
        $input3 = [ "country" => "DKK" ];
        $this->assertEquals(["currency" => "DKK"], arrgh_uintersect_assoc($input1, $input2, $this->string_compare_function));
        $this->assertEquals([], arrgh_uintersect_assoc($input1, $input3, $this->string_compare_function));
    }

    public function testUintersectUassoc()
    {
        $input1 = [ "currency" => "DKK", "live" => true];
        $input2 = [ "currency" => "DKK" ];
        $input3 = [ "country" => "DKK" ];
        $this->assertEquals(["currency" => "DKK"], arrgh_uintersect_uassoc($input1, $input2, $this->string_compare_function, $this->string_compare_function));
        $this->assertEquals([], arrgh_uintersect_uassoc($input1, $input3, $this->string_compare_function, $this->string_compare_function));
    }

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

    // array_search
    public function testSearch()
    {
        $input = [ 0 => "blue", 1 => "red", 2 => "green", 3 => "red" ];
        $input2 = [ "name" => "Michael", "nickname" => "Pacmanche" ];
        $this->assertEquals(2, arrgh_search($input, "green"));
        $this->assertEquals("nickname", arrgh_search($input2, "Pacmanche"));
    }

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

    // array_multisort
    public function testMultisort()
    {
        $input1 = [100, 1, 10, 1000];
        $input2 = [1, 3, 2, 4];
        $input3 = [1, 9, 4, 12];
        $this->assertEquals([[1, 10, 100, 1000], [3, 2, 1, 4]], arrgh_multisort($input1, $input2));
        $this->assertEquals([1, 10, 100, 1000], arrgh_multisort($input1));
        $multidimensional = [$input1, $input3];
        $this->assertEquals([[1, 10, 100, 1000], [9, 4, 1, 12]], arrgh_multisort($multidimensional[0], $multidimensional[1]));
    }

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

    // array_walk_recursive
    public function testWalkRecursive()
    {
        $input = [ "person" => [ "name" => "Mona", "age" => 45 ], "statement" => "for sure!"];
        $output_string = "";
        $output = arrgh_walk_recursive($input, function ($item, $key) use (&$output_string) {
            if ($key === "name") {
                $item = $item . " is";
            }
            $output_string .= $item . ($key === "statement" ? "": " ");
        });
        $this->assertEquals("Mona is 45 for sure!", $output_string);
        $this->assertEquals($input, $output);
    }

    // arsort (doesn't mess up order in PHP5)
    public function testArsort()
    {
        $input = [
            "Jakob II" => 42,
            "Jakob I"  => 42,
            "Ginger"   => 18,
        ];

        $output = arrgh_arsort($input);
        $this->assertEquals(["Jakob II", "Jakob I", "Ginger"], array_keys($output));
    }

    // asort
    public function testAsort()
    {
        $input = [
            "Jakob I"  => 42,
            "Jakob II" => 42,
            "Ginger"   => 18,
        ];

        $output = arrgh_asort($input);
        $this->assertEquals([ "Ginger", "Jakob I", "Jakob II" ], array_keys($output));
    }

    // krsort
    public function testKrsort()
    {
        $input = [
            "img12.png" => 1,
            "Img10.png" => 1,
            "img2.png" => 1,
            "img1.png" => 1,
        ];
        $this->assertEquals([
            "img2.png" => 1,
            "img12.png" => 1,
            "Img10.png" => 1,
            "img1.png" => 1,
        ], arrgh_krsort($input));

        $this->assertEquals([
            "img12.png" => 1,
            "Img10.png" => 1,
            "img2.png" => 1,
            "img1.png" => 1,
        ], arrgh_krsort($input, SORT_NATURAL));
    }

    // ksort
    public function testKsort()
    {
        $input = [
            "img12.png" => 1,
            "Img10.png" => 1,
            "img2.png" => 1,
            "img1.png" => 1,
        ];
        $this->assertEquals([
            "img1.png" => 1,
            "Img10.png" => 1,
            "img12.png" => 1,
            "img2.png" => 1,
        ], arrgh_ksort($input));

        $this->assertEquals([
            "img1.png" => 1,
            "img2.png" => 1,
            "Img10.png" => 1,
            "img12.png" => 1,
        ], arrgh_ksort($input, SORT_NATURAL));
    }

    // natcasesort
    public function testNatcasesort()
    {
        $input = ["img12.png", "Img10.png", "img2.png", "img1.png"];
        $this->assertEquals([
            "img1.png",
            "img2.png",
            "Img10.png",
            "img12.png",
        ], array_values(arrgh_natcasesort($input)));
    }

    // natsort
    public function testNatsort()
    {
        $input = ["img12.png", "Img10.png", "img2.png", "img1.png"];
        $this->assertEquals([
            "Img10.png",
            "img1.png",
            "img2.png",
            "img12.png",
        ], array_values(arrgh_natsort($input)));
    }

    // rsort
    public function testRsort()
    {
        $input = array_merge(range("a", "m"), array_reverse(range("n", "z")));
        $expected = array_reverse(range("a", "z"));
        $this->assertEquals("a", $input[0]);
        $this->assertEquals($expected, arrgh_rsort($input));
    }

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

    // uasort
    public function testUasort()
    {
        $input = [
            "Jakob I" => 42,
            "Jakob II" => 42,
            "Ginger" => 18,
        ];

        $output = arrgh_uasort($input, $this->number_compare_function);
        $this->assertEquals([ "Ginger", "Jakob I", "Jakob II" ], array_keys($output));
    }

    // uksort
    public function testUksort()
    {
        $input = [
            "Jakob I" => 42,
            "Jakob II" => 18,
            "Ginger" => 42,
        ];

        $output = arrgh_uksort($input, function ($a, $b) { return strcasecmp(trim($a, " I"), trim($b, " I")); });
        $this->assertEquals([ "Ginger", "Jakob I", "Jakob II" ], array_keys($output));
    }

    public function testUsort()
    {
        $input = [
            [ "name" => "Jakob", "age" => 42 ],
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 42 ],
        ];
        $expected_result = [
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Ginger", "age" => 42 ],
            [ "name" => "Jakob", "age" => 42 ],
        ];

        $output = arrgh_usort($input, function ($a, $b) {
            return $a["age"] - $b["age"];
        });

        $this->assertEquals([
            [ "name" => "Topher", "age" => 18 ],
            [ "name" => "Jakob", "age" => 42 ],
            [ "name" => "Ginger", "age" => 42 ],
        ], $output);
    }

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

    public function testRand()
    {
        $input = [1, 2, 3, 4];
        $max = 0;
        for ($i = 0; $i < 15 ; $i++) {
            $rand = arrgh_rand($input);
            if ($rand > $max) {
                $max = $rand;
            }
        }
        $this->assertEquals(count($input) - 1, $max);
    }

    public function testShift()
    {
        $input = [1, 2, 3, 4];
        $this->assertEquals(1, arrgh_shift($input));
        $input = [1, 2, 3, 4];
        $this->assertEquals(1, arrgh($input)->shift());
    }
    public function testUnshift()
    {
        $input = [1, 2, 3, 4];
        $this->assertEquals([0, 1, 2, 3, 4], arrgh_unshift($input, 0));
        $input = [1, 2, 3, 4];
        $this->assertEquals([0, 1, 2, 3, 4], arrgh($input)->unshift(0)->toArray());
    }
}
