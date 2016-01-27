# Arrgh

A cleanup of the messy array API in PHP. 

PHP is known for its many messy APIs. Bad naming, order of arguments, passing by reference or returning a value.

_Arrgh_ in short:

* All functions will have an array as the first argument
* All functions will return an array (when you expect it)
* All functions that you know and love (hmm) are there, plus a few more
* Optional chainable API
* Optional function based API
* Optional OO based API

## Examples

> TL;DR;

Normally `usort()` wouldn't return the sorted array:

    return Arrgh::usort($products, function ($a, $b) { ... });

`array_map` has a different order of arguments, but in _Arrgh_ arrays are always first:

    arrgh_map($books, $callable);

Chain functions together:

    return Arrgh::chain($product)->array_reverse()->array_pop();

This is the same:

    return Arrgh::_reverse($product)->pop();

## Array as first argument

_Arrgh_ makes use of the built in functions if they have array(s) as the first arguments:

    array_reduce ( array $array , callable $callback [, mixed $initial = NULL ] )

For misbehaving functions like `array_map` the arguments are shuffled around about:

    array_map ( callable $callback , array $array1 [, array $... ] )

In _Arrgh_ we restore sanity:

    arrgh_map($products, function ($product) {
        return $product->code();
    });

All functions that takes one or more arrays now takes them as their first arguments:

    array_key_exists
    array_map
    array_search
    implode
    in_​array
    join

## All functions returns

Most of the functions that makes alterations to arrays like sorting all pass the input array by reference like:

    usort ( array &$array , callable $value_compare_func )

This make functional programming difficult or less fluent in PHP.

In _Arrgh_ all array functions will return an array, unless of course a value is being computed.

These functions will now return a result:

[array multisort](http://php.net/manual/en/function.array_multisort.php)  
[array push](http://php.net/manual/en/function.array_push.php)  
[array splice](http://php.net/manual/en/function.array_splice.php)  
[array walk](http://php.net/manual/en/function.array_walk.php)  
[array walk recursive](http://php.net/manual/en/function.array_walk_recursive.php)  
[arsort](http://php.net/manual/en/function.arsort.php)  
[asort](http://php.net/manual/en/function.asort.php)  
[krsort](http://php.net/manual/en/function.krsort.php)  
[ksort](http://php.net/manual/en/function.ksort.php)  
[natcasesort](http://php.net/manual/en/function.natcasesort.php)  
[natsort](http://php.net/manual/en/function.natsort.php)  
[rsort](http://php.net/manual/en/function.rsort.php)  
[shuffle](http://php.net/manual/en/function.shuffle.php)  
[sort](http://php.net/manual/en/function.sort.php)  
[uasort](http://php.net/manual/en/function.uasort.php)  
[uksort](http://php.net/manual/en/function.uksort.php)  
[usort](http://php.net/manual/en/function.usort.php)  

This means you can now do this:

    // Top 5 most expensive products

    function getTop5($products) {
        return arrgh_slice(arrgh_usort($products, function ($p1, $p2) {
            ...
        }), 0, 5);
    }

I just look nicer than:

    array_usort($products, function ($p1, $p2) {... };
    return array_slice($products, 0, 5);

**SPOILER** .. or something juicer like this:

    arrgh($products)->usort($products, function ($p1, $p2) { ... })->slice(0, 5);

This is an example of the chainable API. See below

## Choose your style

_Arrgh_ comes in three styles for your temperament: 

* Pure functions
* Static class style
* Chainable object style

### Pure functions

The _Arrgh_ repertoire of functions is include in the global scope, so that you can use the functions anywhere. Just like the native array functions:

    arrgh_replace($defaults, $params);

The constructor function `aargh()` lets you start a chain:

    arrgh($defaults)->replace($params);

**Note**: Because I'm lazy this requires `eval()` to be enabled. _Arrgh_ uses `eval()` build the functions based on the `Arrgh` class.

On the upside if you find the `arrgh_` prefix of the functions to piraty you can change it to something else:

    define("ARRGH", true);
    define("ARRGH_PREFIX", "arr");

Then `arrgh_map` becomes `arr_map`. 

**Note**: Function are disabled by default. Simply define `ARRGH` to `true` to enable.

### Static

You can use the static functions on `Arrgh`:

    Arrgh::array_flip($music);
    
All static methods takes an array as the first input and returns an array. Even sort:

    return Arrgh::sort($big_numbers);

But of course you can break out of the static style like:

    // Return the sum of `$big_numbers`
    Arrgh::arrgh($big_numbers)->sort()->reduce(function ($k, $v) { return $k += $v });

A synonym of `arrgh` is `chain`. A shorthand for both is to prefix any method with _underscore_:

    Arrgh::_sort($big_numbers)->reduce(function ($k, $v) { return $k += $v });
    
Now sort no longer returns a sort array but a chainable object.

### Chains

Chains can be created in a couple of ways.

With the `arrgh()` function:

    arrgh($array)->reverse();

With the static methods `Arrgh::arrgh()` and `Arrgh::chain()`:

    Arrgh::chain($array)->reverse();
    
With the static method shorthand (_):

    Arrgh::_reverse($array);
    
Or by creating a chainable object:

    $videos = new Arrgh($videos);
    $media = $videos->merge($music)->shuffle()->slice(0, 3);

All the chains above only manipulate the arrays, but never return a value. Except for the `reduce` method that returns a numeric value. To get the resulting 

    $media->toArray();

## All functions are there

All the functions from the PHP manual [Array Functions](http://php.net/manual/en/ref.array.php) section are supported.

If you use function style the methods are prefixed with `arrgh_` except for functions starting with `array_` (in that case it is replaced):

    array_map => arrgh_map
    usort => arrgh_usort

For static and chainable style you can use the orignal names like:

    Arrgh::array_flip($bucket);

You can use camelCase and also omit `array_`:

    Arrgh::arrayFlip($bucket);
    Arrgh::flip($bucket);
    
These are all the same:

    arrgh($bucket)->array_walk_recursive(...)
    arrgh($bucket)->arrayWalkRecursive(...)
    arrgh($bucket)->walk_recursive(...)
    arrgh($bucket)->walkRecursive(...)

I lied. These functions are not supported:

[compact](http://php.net/manual/en/function.compact.php)  
[extract](http://php.net/manual/en/function.extract.php)  
[current](http://php.net/manual/en/function.current.php)  
[each](http://php.net/manual/en/function.each.php)  
[key](http://php.net/manual/en/function.key.php)  
[next](http://php.net/manual/en/function.next.php)  
[pos](http://php.net/manual/en/function.pos.php)  
[prev](http://php.net/manual/en/function.prev.php)  
[reset](http://php.net/manual/en/function.reset.php)  

But then you get:

   TODO list new functions


## TODO implement

* all

* collapse
* contains
* each
* every
* first
* flatten
* forget
* forPage
* get
* groupBy
* has
* implode
* isEmpty
* keyBy
* keys
* last
* map
* merge
* pluck
* pop
* prepend
* pull
* push
* put
* random
* reduce
* reject
* reverse
* search
* shift
* shuffle
* slice
* sort
* sortBy
* sortByDesc
* splice
* sum
* take
* toArray
* toJson
* transform
* unique
* values
* where
* whereLoose
* zip
* tail /end
* head 



/**
 * splice extracted is thrown away (maybe these functions can set $this->value and $this->array and then add function ->value())

TODO is_array(), explode(), implode(), split(), preg_split(), and unset().