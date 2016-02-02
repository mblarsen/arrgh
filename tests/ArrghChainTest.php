<?php

class ArrghChainTest extends PHPUnit_Framework_TestCase
{
    public function testExclaim()
    {
        $input = [1, 10, 100];
        $this->assertEquals(
            [100, 10, 1],
            arrgh($input)
                ->map(function ($i) { return log10($i); })
                ->reverse()
                ->map(function ($i) { return pow(10, $i); })
                ->toArray()
        );
    }
    
    public function testChildren()
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

        $children = arrgh($array)
            ->map(function ($person) { return isset($person["children"]) ? $person["children"] : null; })
            ->filter()   // remove nulls
            ->collapse() // make one array of all children
            ->map(function ($child) { 
                if ($child["sex"] === "female") { return $child["name"]; }
                return null;
            })
            ->filter()   // remove nulls
            ->toArray();
        $this->assertEquals([ "Mona", "Lisa" ], $children);
        
        $this->assertEquals([ "Mona", "Lisa" ], arrgh($array)->get([ "children.!$.name",
            function ($item, $index) { return $item['sex'] === 'female'; }
        ], true)->toArray());
    }
}
