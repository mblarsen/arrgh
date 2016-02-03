<?php

class ArrghIteratorTest extends PHPUnit_Framework_TestCase
{
    public function testIterator()
    {
        $arr = arrgh([1, 2, 3, 4, 5]);
        $content = [];
        foreach ($arr as $value) {
            $content[] = $value;
        }
        $this->assertEquals([1, 2, 3, 4, 5], $content);

        $arr = arrgh([[3, 2, 1], [5, 4]]);
        $content = arrgh([]);
        foreach ($arr as $value) {
            $content = $content->merge($value->reverse());
        }
        $this->assertEquals([1, 2, 3, 4, 5], $content->toArray());
    }
}
