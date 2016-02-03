<?php

class ArrghChainTest extends PHPUnit_Framework_TestCase
{
    public function testStartChain()
    {
        // Function constructor
        $this->assertEquals(42, arrgh([42])->pop());
        // Static constructors
        $this->assertEquals(42, Arrgh::arrgh([42])->pop());
        $this->assertEquals(42, Arrgh::chain([42])->pop());
        // Object constructor
        $this->assertEquals(42, (new Arrgh([42]))->pop());
        // Create from Arggh object
        $this->assertEquals(42, arrgh(arrgh([42]))->pop());
        // Static chain method prefix
        $this->assertEquals(42, Arrgh::_filter([42])->pop());
        $this->assertEquals(42, Arrgh::_range(0, 42)->pop());
    }

    public function testTerminators()
    {
        $input = [1, 2, 3, 4, 5];
        $this->assertEquals(5, arrgh($input)->pop());
        $this->assertEquals(15, arrgh($input)->sum());
        $this->assertEquals(5, arrgh($input)->count());
        $this->assertEquals("1,2,3,4,5", arrgh($input)->join(","));
        $this->assertEquals(5, arrgh($input)->max());
        $this->assertEquals(1, arrgh($input)->min());
        $this->assertEquals(5, arrgh($input)->sizeof());
    }

    public function testKeepChain()
    {
        $input = [1, 2, 3, 4, 5];
        $this->assertEquals(10, arrgh($input)->keepChain()->pop()->keepChain(false)->sum());
    }

    public function testArrghInput()
    {
        $arr_inner = new Arrgh([1, 2 ,3]);
        $arr_out = new Arrgh($arr_inner);
        $this->assertEquals([1, 2, 3], $arr_out->toArray());
    }
}
