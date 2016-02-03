<?php

class ArrghArrayAccessTest extends PHPUnit_Framework_TestCase
{
    public function testArrayAccess()
    {
        $arr = arrgh([1, 2, 3, 4, 5]);
        
        // Add element without key
        $arr[] = 6;
        $this->assertTrue(isset($arr[5]));
        
        // Get element
        $pos5 = $arr[5];
        $this->assertEquals(6, $pos5);
        
        // Add element with key
        $arr[5] = 7;
        $this->assertEquals(22, $arr->sum());
        
        // Check that the internal array works with chain after ArrayAccess operations
        $this->assertEquals(15, $arr->keepChain()->pop()->keepChain(false)->sum());
        
        // Unset element
        unset($arr[4]);
        $this->assertEquals(10, $arr->sum());
    }
}
