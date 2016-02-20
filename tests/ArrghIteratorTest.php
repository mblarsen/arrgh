<?php

namespace Arrgh;

class ArrghIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testIterator()
    {
        $arr = arr([1, 2, 3, 4, 5]);
        $content = [];
        foreach ($arr as $value) {
            $content[] = $value;
        }
        $this->assertEquals([1, 2, 3, 4, 5], $content);

        $arr = arr([[3, 2, 1], [5, 4]]);
        $content = arr([]);
        foreach ($arr as $value) {
            $content = $content->merge($value->reverse());
        }
        $this->assertEquals([1, 2, 3, 4, 5], $content->toArray());
    }

    public function testWithKey()
    {
        $arr = arr([ "name" => "Topher" ]);
        foreach ($arr as $key => $value) {
            $this->assertEquals("name", $key);
            $this->assertEquals("Topher", $value);
        }
    }
}
