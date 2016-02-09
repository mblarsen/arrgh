# Arrgh—a sane PHP array library

[![Join the chat at https://gitter.im/mblarsen/arrgh](https://badges.gitter.im/mblarsen/arrgh.svg)](https://gitter.im/mblarsen/arrgh?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/mblarsen/arrgh.svg?branch=master)](https://travis-ci.org/mblarsen/arrgh) [![Coverage Status](https://coveralls.io/repos/github/mblarsen/arrgh/badge.svg?branch=master)](https://coveralls.io/github/mblarsen/arrgh?branch=master) [![Support via Gittip](http://img.shields.io/gittip/mblarsen.svg?style=flat)](https://www.gittip.com/mblarsen/) [![Get help on Codementor](https://cdn.codementor.io/badges/get_help_github.svg)](https://www.codementor.io/mblarsen?utm_source=github&utm_medium=button&utm_term=mblarsen&utm_campaign=github)

The goal of _Arrgh_ is to provide a more uniform library for working with arrays in PHP.

* Arrays as first parameter. The existing API for arrays can be very confusing. For some functions the input array is the first parameter on others the last. Moreover some functions returns a result others don't (this mostly has to do with passing of input as reference.)
* Not a re-write but a remapping of parameters to native functions. E.g. `arrgh_map` moves the `$callable` as the last parameter.
* Comes in three flavors: functions (`arrgh_map`), static (`Arrgh::map`), objects/chainable (`$array->map()`).
* Adds missing functions like: `map_ass` (associative mapping function), `collapse`, `get` (a dot-path getter), `sortBy` and more. (see [Additional functions](#additional-functions))
* Provides aid for working with sort/compare on both PHP5 and PHP7 which treats equals differently.
* Lets you use native function names or shorter ones in snake or camelCase. E.g. `$array->array_map()`, `$array->arrayMap()`, `$array->map()`.

# How to use

    composer require mblarsen/arrgh

If you want to make use of function-style you have to define the following before `vendor/autoload` require.

    define("ARRGH", true);

Now you can use functions like this anywhere in your code:

    arrgh_reverse([1, 2, 3]);

You can change the function prefix using:

    define("ARRGH_PREFIX", "arr");

Now `arrgh_reverse` becomes:

    arr_reverse([1, 2, 3]);

_Note: changing the function prefix requires the use of `eval()`. If `eval()` is disabled *Arrgh* will throw an exception. *Arrgh* comes prebuild with arrgh and arr prefixes, so for these `eval()` is not needed._

## Examples

Functions takes array as the first parameter in every case—no more looking up in the documents:

    arrgh_map($input, $callable);
    arrgh_join($input, ",");

Chain functions together:

    arrgh($input)->reverse()->slice(0, 5)->sum();

Powerful get function using _dot-paths_:

    // People and their children
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

    // Return all children's names
    arrgh_get($array, "children.name");      // returns [ ["Mona", "Lisa"] , ["Joe"] ]
    arrgh_get($array, "children.name", true) // returns [ "Mona", "Lisa", "Joe" ]

Use buil-in select functions, select by index:

    arrgh_get($array, "children.0.name");    // returns [ ["Mona"], ["Joe"] ]
    arrgh_get($array, "children.1.name");    // returns [ ["Lisa"] ]
    arrgh_get($array, "children.-1.name");   // returns [ ["Lisa"], ["Joe"] ]

... or role your own select functions, return names of all female children:

    $children = arrgh($array)->get([ "children.!$.name",
        function ($item, $index) { return $item['sex'] === 'female'; }
    ])->toArray();

    // returns [ Mona, Lisa ]

To achieve the same using chain API (which is also pretty concise) it looks like this:

    $children = arrgh($array)
        ->map(function ($person) { return isset($person["children"]) ? $person["children"] : null; })
        ->filter()
        ->collapse()
        ->map(function ($child) {
            if ($child["sex"] === "female") { return $child["name"]; }
            return null;
        })
        ->filter()
        ->toArray();

## Array as first argument

_Arrgh_ doesn't rewrite the array algorithms but remaps parameters to native functions. E.g. `arrgh_map` moves the `$callable` as the last parameter.

    array_map ( callable $callback , array $array1 [, array $... ] )

Becomes:

    arrgh_map ( array $array1 [, array $... ] , callable $callback )

Example usage:

    arrgh_map($products, function ($product) {
        return $product->code();
    });

All functions that takes one or more arrays now takes them as their first arguments:

    array_key_exists($key, $array)         => ($array, $key)
    in_​array($key, $array)                 => ($array, $key)
    array_search($search, $array)          => ($array, $search)
    implode($glue, $array)                 => ($array, $glue);
    join($glue, $array)                    => ($array, $glue);
    array_map($callable, $array[, arrayN]) => ($array[, arrayN], $callable)

## All functions returns

Most of the functions that makes alterations to arrays like sorting all pass the input array by reference like:

    usort ( array &$array , callable $value_compare_func )

This make functional programming difficult and less fluent in PHP.

In _Arrgh_ all array functions will return an array, unless of course a value is being computed (`sum`, `pop`, `min`, `max`, etc).

These functions will now return a result:

[array multisort](http://php.net/manual/en/function.array_multisort.php), [array push](http://php.net/manual/en/function.array_push.php), [array splice](http://php.net/manual/en/function.array_splice.php), [array walk](http://php.net/manual/en/function.array_walk.php), [array walk recursive](http://php.net/manual/en/function.array_walk_recursive.php), [arsort](http://php.net/manual/en/function.arsort.php), [asort](http://php.net/manual/en/function.asort.php), [krsort](http://php.net/manual/en/function.krsort.php), [ksort](http://php.net/manual/en/function.ksort.php), [natcasesort](http://php.net/manual/en/function.natcasesort.php), [natsort](http://php.net/manual/en/function.natsort.php), [rsort](http://php.net/manual/en/function.rsort.php), [shuffle](http://php.net/manual/en/function.shuffle.php), [sort](http://php.net/manual/en/function.sort.php), [uasort](http://php.net/manual/en/function.uasort.php), [uksort](http://php.net/manual/en/function.uksort.php), [usort](http://php.net/manual/en/function.usort.php)

This means where you had to like this:

    // Top 5 most expensive products
    array_usort($products, function ($p1, $p2) { ... };
    return array_slice($products, 0, 5);

You can now do like this:

    // Top 5 most expensive products
    return arrgh_slice(arrgh_usort($products, function ($p1, $p2) { ... }), 0, 5);

Or you could use chains like this [[see more below](#chain-style)]:

    // Top 5 most expensive products
    return arrgh($products)
        ->usort($products, function ($p1, $p2) { ... })
        ->slice(0, 5);

## Choose your style

_Arrgh_ comes in three styles for your temperament:

* [Function style](#function-style)
* [Static style](#static-style)
* [Chain style](#chain-style)

### Function style

The _Arrgh_ repertoire of functions is include in the global scope, so that you can use the functions anywhere. Just like the native array functions:

    arrgh_replace($defaults, $params);

The constructor function `aargh()` lets you start a chain:

    arrgh($defaults)->replace($params);

_Note: See [How to use](#how-to-use) on how to enable function-style._

### Static style

You can use the static functions on `Arrgh`:

    Arrgh::array_flip($music);
    // or
    Arrgh::flip($music);

All static methods takes an array as the first input and returns an array. Even sort:

    return Arrgh::sort($big_numbers);

You can break out of static-style and start a chain like this:

    // Return the sum of `$big_numbers`
    Arrgh::arrgh($big_numbers)
        ->sort()
        ->reduce(function ($k, $v) { return $k += $v });

A synonym of `arrgh` is `chain`. A shorthand for both is to prefix any method with _underscore_:

    Arrgh::_sort($big_numbers)
        ->reduce(function ($k, $v) { return $k += $v });

_sort_ now returns chainable object.

### Chain style

Chains can be created in a couple of ways.

Using the `arrgh()` function:

    arrgh($array)->reverse();

Using the static methods `Arrgh::arrgh()` or `Arrgh::chain()`:

    Arrgh::chain($array)->reverse();

Using the static method shorthand (_):

    Arrgh::_reverse($array);

Or by creating a chainable object:

    $videos = new Arrgh($videos);
    $media = $videos->merge($music)->shuffle()->slice(0, 3);

When you are working with objects all methods returns an object, not an actual array. To get a native PHP array invoke the `toArray()` method:

    $media->toArray();

_Note: *Arrgh* implements both [ArrayAccess<sup>php</sup>](http://php.net/manual/en/class.arrayaccess.php) and [Iterator<sup>php</sup>](http://php.net/manual/en/class.iterator.php) so you can [use an Arrgh object as an array](#works-like-array)._

In case you want to preserve the array rather than the result of a terminating functions like e.g. `pop()`, you can use `keepChain()`:

    arrgh([1, 2, 3])->pop(); // will return 3

With use of `keepChain()` we'll get the array instead:

    arrgh([1, 2, 3])->keepChain()->pop(); // will return an Arrgh object with the array [1, 2]

If you want to break the chain again. For example to get the sum of the remaining elements you can:

    arrgh([1, 2, 3])->keepChain()->pop()->keepChain(false)->sum(); // returns 3

If `->keepChain(false)` had been left out `sum()` would also have returned the `Arrgh` object.

The same expression can be written using `keepOnce()`:

    arrgh([1, 2, 3])->keepOnce()->pop()->sum(); // returns 3

## All functions are there

All the functions from the PHP manual [Array Functions<sup>php</sup>](http://php.net/manual/en/ref.array.php) section are supported.

If you use function style the methods are prefixed with `arrgh_` except for functions starting with `array_` (in that case it is replaced):

    array_map => arrgh_map
    usort => arrgh_usort

These functions are not supported:

[compact](http://php.net/manual/en/function.compact.php), [extract](http://php.net/manual/en/function.extract.php), [current](http://php.net/manual/en/function.current.php), [each](http://php.net/manual/en/function.each.php), [key](http://php.net/manual/en/function.key.php), [next](http://php.net/manual/en/function.next.php), [pos](http://php.net/manual/en/function.pos.php), [prev](http://php.net/manual/en/function.prev.php), [reset](http://php.net/manual/en/function.reset.php)

## Additional functions

In addition to [Array Functions<sup>php</sup>](http://php.net/manual/en/ref.array.php) _Arrgh_ provides these functions:

* `map_ass(array, function)`: Map function that works on associative arrays. Map function `function ($key, $value)`.
* `sort_by(array, key|function)`: Sort a collection of items by a key or a function. Sort function `function ($item)`.
* `contains(array, [key])`: Looks through a collection for a certain value and returns true or falls. Can be restricted to a key.
* `collapse(array)`: Collapses an array of arrays into one. E.g. `[[1, 2], [3, 4]]` becomes `[1, 2, 3, 4]`
* `except(array, key|array)`: Return all collections of items having some keys in `key|array` stripped.
* `only(array, key|array)`: Like `except` but will only return items with the keys in `key|array`.
* `get(array, path)`: A powerful getter of multidimensional arrays.
* `isCollection(array)`: Tells if an array is a collection (as opposed ot an associative array)
* `depth(array)`: Tells the depth of arrays of arrays (excluding associative arrays)
* `head(array)`: Synonym for shift
* `tail(array)`: Returns everything but the head. E.g. tail([1, 2, 3]) outputs [2, 3]
* `first(array)`: Synonym for shift
* `last(array)`: Synonym for pop
* `partition(array, callable)`: Splits array into two arrays determined by callable function

## Works like array

_Arrgh_ implements [ArrayAccess<sup>php</sup>](http://php.net/manual/en/class.arrayaccess.php) and [Iterator<sup>php</sup>](http://php.net/manual/en/class.iterator.php), so you can use it as an array.

Here is an example using foreach:

    $arr = arrgh([1, 2, 3, 4, 5]);
    foreach ($arr as $value) {
        echo $value . PHP_EOL;
    }

You can push values onto array like native arrays:

    echo $arr->sum(); // 15
    $arr[] = 6;
    echo $arr->sum(); // 21

Array values are returned as `Arrgh` objects:

    $arr = arrgh([[3, 2, 1], [5, 4]]);
    $content = arrgh([]);
    foreach ($arr as $value) {
        $content = $content->merge($value->reverse());
    }
    $content->toArray(); // returns array [ 1, 2, 3, 4, 5 ];

_Note: PHP's native functions can only take arrays as parameters, so that is a limitation. But you are not using them anyway are you?_

## PHP5 vs PHP7

At the time of writing if PHP5 and PHP7 treats equal values returned by comparable-functions differently.

For example. The following unittest will fail in PHP 5.6.x and not in 7:

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

    //
    // Actual ouput in PHP 5.6.x:
    // [
    //     [ "name" => "Topher", "age" => 18 ],
    //     [ "name" => "Ginger", "age" => 42 ],
    //     [ "name" => "Jakob", "age" => 42 ],
    // ]
    //
    // Actual ouput in PHP 7:
    // [
    //     [ "name" => "Topher", "age" => 18 ],
    //     [ "name" => "Jakob", "age" => 42 ],
    //     [ "name" => "Ginger", "age" => 42 ],
    // ]

PHP5 and PHP7 treats the items in an array with a compare result differently. You have to take this into account if you code for both versions. While PHP5 changes the order, PHP7 does not have this side-effect.

_Arrgh_ elevates this problem by providing the correct integer depending on which version is running. So when running your custom compare functions you can do like this:

    usort($input, function ($a, $b) {
        return Arrgh::getSortDirection($a["age"] - $b["age"]);
    });

or using _Arrgh_:

    arrgh_usort($input, function ($a, $b) {
        return Arrgh::getSortDirection($a["age"] - $b["age"]);
    });

See example in the unit test `ArrghGeneralTest::testPhpVersionFail*`.

**New as of v0.6.0** for all _Arrgh_ functions you don't have to take this problem into account, so you can simply do like this:

    arrgh_usort($input, function ($a, $b) {
        return $a["age"] - $b["age"];
    });

Internally the callable is wrapped in PHP version aware callable that inspects the result and returns a value according to the PHP version.

## Change log

**v0.7.0**

* Changed: Rewrote `isCollection()` and `depth()`. `[]` is now a collection. Depth is now zero based.
* Changed: Native `array_column()` will filter away null values—i.e when column is missing. This is equivalent to `array_filter(array_map(callable, array))`. This means that you cannot use
  `array_column()` for `array_multisort()` since array size no longer matches. This behaviour has been changed in _Arrgh_, so to achieve the same result as the native column-function you'll ned to filter it afterwards.

**v0.6.1**

* New: Added `head()`, `tail()`, `first()`, `last()`, `partition()`.

**v0.6.0**

* New: Certain sort and compare functions that takes a callable will now automatically have their callable wrapped in a PHP version aware callable.
* New: Terminator methods will now return an _Arrgh_ object when the result is an array.
* New: `asort()` behaves like `uasort()` so it is now now mapped to `uassort()` with a wrapped callable
* New: `shift()` and `unshift()` was missing.
* Changed: `getPhpSortDirection()` is now `getSortDirection()`

**v0.5.8**

* Changed: `getSortDirection()` takes a computed direction.

**v0.5.7**

* New: `keepOnce()` added. Will break the chain after keeping it once

**v0.5.5**

* New: `min()` and `max` added
* Added script to build functions in case anyone needs it

**v0.5.4**

* Bugfixes
* New: Global functions are now pre-build for `arrgh` prefix

**v0.5.1**

* Bugfix: sortBy DESC for array with two items would fail
* Bugfix: sortBy fix for discrepancy for how zero-value sort comparison is handled in PHP5 vs PHP7

**v0.5.0**

* Fixed: Collapsing was not reliable, fixed + unittests added
* Changed: `!>` replaced with option to use negative index. So `children.!>.name` to get name of last child is now: `children.-1.name`
* New: Added `depth()`

**v0.4.0**

* New: Implements ArrayAccess and Iterator. Iterator returns Arrgh instead of arrays.
* New: _Arrgh_ objects can be passed as arguments instead of arrays.
* New: _copyValue_ type functions like `array_pop` that changes the array but also returns a value
  now sets the array to it can be access with `toArray()`.
* New: Default behaviour of value functions is to return the value. E.g. `pop()`. The new function `keepChain()` will return the `Arrgh` object rather than the value.

**v0.3.0**

* Changed `dotGet` to `get`

**v0.2.1**

* Added: `get()` function [[see examples](#examples)]
* Added: `isCollection()` function

## TODO

* Change unpacking to calls of native functions that uses pass-by-reference to use `call_user_func_array` since `hhvm` doesn't support it.
