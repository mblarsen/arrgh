<?php

/**
 * A chainable array API or a set of static functions, or both.
 *
 * Note: arrgh_* global functions are defined at the end of the file
 */
class Arrgh implements ArrayAccess, Iterator
{
    const PHP_SORT_DIRECTION_56 = 1;
    const PHP_SORT_DIRECTION_7 = -1;
        
    private $array;
    private $array_position;
    private $original_array;
    private $terminate;
    private $keep_once;
    private $last_value;

    private static $php_version;
    private static $php_sort_direction;

    /* Creates a new arrgh array */
    public function __construct($array = [])
    {
        $this->array = $array;
        $this->array_position = 0;
        if ($array instanceof Arrgh) {
            $this->array = $array->toArray();
        }
        $this->_original_array = $this->array;
        $this->terminate = true;
    }

    /* Starts object calls */
    public function __call($method, $args)
    {
        return self::invoke($method, $args, $this);
    }

    /* Returns an array */
    public function toArray()
    {
        return $this->array;
    }

    public function keep()
    {
        return $this->keepChain(true);
    }
    public function keepOnce()
    {
        return $this->keepChain(true, true);
    }
    public function keepChain($value = true, $keep_once = false)
    {
        $this->terminate = !$value;
        $this->keep_once = $keep_once;
        return $this;
    }
    public function breakChain()
    {
        return $this->keepChain(false);
    }

    /* ArrayAccess */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /* ArrayAccess */
    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    /* ArrayAccess */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    /* ArrayAccess */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    /* Iterator */
    public function current()
    {
        $value = $this->array[$this->array_position];
        if (is_array($value)) {
            return new Arrgh($value);
        }
        return $value;
    }

    /* Iterator */
    public function key()
    {
        return $this->array_position;
    }

    /* Iterator */
    public function next()
    {
        ++$this->array_position;
    }

    /* Iterator */
    public function rewind()
    {
        $this->position = 0;
    }

    /* Iterator */
    public function valid()
    {
        return isset($this->array[$this->array_position]);
    }

    /* Creates a new arrgh array. Synonym for: chain() */
    static public function arrgh($array = [])
    {
        return self::chain($array);
    }

    /* Creates a new arrgh array. Synonym for: arrgh() */
    static public function chain($array = [])
    {
        return new self($array);
    }

    /* Starts object calls */
    static public function __callStatic($method, $args)
    {
        if ($method[0] === "_") {
            $method = substr($method, 1);
            $_args = $args;
            $first_argument = array_shift($args);
            if (is_array($first_argument)) {
                return self::chain($first_argument)->$method(...$args);
            }
            return self::chain()->$method(...$_args);
        }
        return self::invoke($method, $args);
    }

    static public function allFunctions()
    {
        return [
            "_arrgh"        => self::$arrgh_functions,
            "_call"         => self::$simple_functions,
            "_rotateRight"  => self::$reverse_functions,
            "_swapTwoFirst" => self::$swapped_functions,
            "_copy"         => self::$mutable_functions,
            "_copyMultiple" => self::$mutable_functions_multiple,
            "_copyValue"    => self::$mutable_value_functions,
        ];
    }
    
    static public function getSortDirection($direction = null)
    {
        if (self::$php_version === null) {
            self::$php_version = explode(".", phpversion());
            self::$php_sort_direction = self::$php_version[0] >= 7 ? self::PHP_SORT_DIRECTION_7 : self::PHP_SORT_DIRECTION_56;
        }
        if ($direction === null || $direction === 0) {
            return self::$php_sort_direction;
        }
        return $direction;
    }

    /* Wraps a callable with the purpose of fixing bad PHP sort implementations */
    static private function wrapCallable($callable)
    {
        $direction = Arrgh::getSortDirection();
        return function ($a, $b) use ($direction, $callable) {
            $result = $callable($a, $b);
            if ($result === 0) return $direction;
            return $result;
        };
    }

    /* Transforms the incoming calls to native calls */
    static private function invoke($method, $args, $object = null)
    {
        self::getSortDirection();

        $snake = strtolower(preg_replace('/\B([A-Z])/', '_\1', $method));
        $function_name = $snake;
        $function_name_prefixed = stripos($method, "array_") === 0 ? $snake : "array_" . $snake;

        $all_function_names = [ $function_name, $function_name_prefixed ];
        $all_functions      = self::allFunctions();

        $matching_handler = null;
        $matching_function = null;
        foreach ($all_functions as $handler => $functions) {
            foreach ($all_function_names as $function) {
                if (in_array($function, $functions)) {
                    $matching_handler  = $handler;
                    $matching_function = $function;
                    break 2;
                }
            }
        }

        if ($matching_function === null) {
            throw new InvalidArgumentException("Method {$method} doesn't exist");
        }
        
        if ($matching_function === "asort") { // "arsort" doen't mess up in PHP5
            $matching_function = "uasort";
            array_push($args, function ($a, $b) { return strcasecmp($a, $b); });
        }

        // If chain unshift array onto argument stack
        if ($object && !in_array($matching_function, self::$starters)) {
            array_unshift($args, $object->array);
        }

        // If some arrays are Arrghs map to array or if callable, wrap it in new callable with
        // info about sort direction.
        $args = array_map(function ($arg) use ($matching_function) { 
            if ($arg instanceof Arrgh) {
                return $arg->array;
            } else if ($arg instanceof Closure) {
                if (in_array($matching_function, self::$reverse_result_functions) && self::$php_version[0] < 7) {
                    return self::wrapCallable($arg);
                }
            }
            return $arg;
        }, $args);

        // Invoke handler
        $result = self::$matching_handler($matching_function, $args, $object);

        if ($object) {
            if (in_array($matching_function, self::$terminators)) {
                if ($object->terminate) {
                    if (is_array($result)) {
                        return new Arrgh($result);
                    }
                    return $result;
                }
                if ($object->keep_once) {
                    $object->terminate = true;
                    $object->keep_once = false;
                }
                $object->last_value = $result;
                return $object;
            }
            $object->array = $result;
            return $object;
        }
        return $result;
    }

    /* Calls the native function directly */
    static private function _call($function, $args)
    {
        return $function(...$args);
    }

    /* Shifts of the first argument (callable) and pushes it to the end */
    static private function _rotateRight($function, $args)
    {
        $first_argument = array_pop($args);
        array_unshift($args, $first_argument);
        return $function(...$args);
    }

    /* Swaps the first two args */
    static private function _swapTwoFirst($function, $args)
    {
        $first_argument = array_shift($args);
        $second_argument = array_shift($args);
        array_unshift($args, $first_argument);
        array_unshift($args, $second_argument);
        return $function(...$args);
    }

    /* Makes a copy of the array and returns it after invoking function */
    static private function _copy($function, $args)
    {
        $array = array_shift($args);
        $result = $function($array, ...$args);
        return $array;
    }
    
    /* If multiple arrays are passed as arguments mulitple will be returned. Otherwise _copy is used */
    static private function _copyMultiple($function, $args)
    {
        $result = $function(...$args);
        $arrays = [];
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $arrays[] = $arg;
            }
        }
        if (count($arrays) === 1) {
            return $arrays[0];
        }
        return $arrays;
    }

    /* Makes a copy of the array and returns it after invoking function */
    static private function _copyValue($function, $args, $object = null)
    {
        $array = array_shift($args);
        $result = $function($array, ...$args);
        if ($object) {
            $object->array = $array;
        }
        return $result;
    }

    static private function _arrgh($function, $args, $object = null)
    {
        $function = "arrgh_" . $function;
        return self::$function(...$args);
    }

    static private function arrgh_map_ass($array, $callable)
    {
        $keys = array_keys($array);
        return array_combine($keys, array_map($callable, $keys, $array));
    }

    /**
     * Sort an array of associative arrays by key. It checks the first two values for type
     * either sorts by number or using strcmp. If a key is missing entries are moved to the top
     * (or bottom depending on $direction)
     */
    static private function arrgh_sort_by($array, $key, $direction = "ASC")
    {
        if (count($array) < 2) {
            return $array;
        }

        $direction_int = strtoupper($direction) === "ASC" ? 1 : -1;

        $usort_function = null;
        if ($key instanceof Closure) {
            usort($array, self::wrapCallable($key));
            if ($direction_int === -1) {
                $array = array_reverse($array);
            }
        } else {
            $compare_function = "strcasecmp";
            if (array_key_exists($key, $array[0]) && is_numeric($array[0][$key]) &&
                array_key_exists($key, $array[1]) && is_numeric($array[1][$key])) {
                $compare_function = null;
            }

            $usort_function = function ($a, $b) use ($compare_function, $key, $direction_int) {
                $a_isset = isset($a[$key]);
                $b_isset = isset($b[$key]);
                if (!$a_isset || !$b_isset) {
                    $result = 0;
                    if ($a_isset xor $b_isset) {
                        $result = $a_isset && !$b_isset ? 1 : -1;
                    }
                } else if ($compare_function === null) {
                    $result = $a[$key] - $b[$key];
                } else {
                    $result = $compare_function($a[$key], $b[$key]);
                }
                $result *= $direction_int;
                return $result;
            };
            usort($array, self::wrapCallable($usort_function));
        }
        return $array;
    }

    static private function arrgh_collapse($array)
    {
        return array_reduce($array, function ($merged, $item) {
            if (is_array($item)) {
                return $merged = array_merge($merged, $item);
            }
            $merged[] = $item;
            return $merged;
        }, []);
    }

    static private function arrgh_contains($array, $search, $key = null)
    {
        $haystack = null;
        if ($key) {
            $haystack = array_column($array, $key);
        } else {
            $haystack = array_reduce($array, function ($merged, $item) {
                return $merged = array_merge($merged, array_values($item));
            }, []);
        }
        return array_search($search, $haystack) !== false;
    }

    static private function arrgh_except($array, $except)
    {
        if (is_string($except)) {
            $except = [ $except ];
        }

        $is_collection = Arrgh::arrgh_is_collection($array);
        $array = $is_collection ? $array : [ $array ];

        $result = array_map(function ($item) use ($except) {
            foreach ($except as $key) {
                unset($item[$key]);
            }
            return $item;
        }, $array);

        if ($is_collection) {
            return $result;
        }
        return $result[0];
    }

    static private function arrgh_only($array, $only)
    {
        if (is_string($only)) {
            $only = [ $only ];
        }

        $is_collection = Arrgh::arrgh_is_collection($array);
        $array = $is_collection ? $array : [ $array ];

        $result = array_map(function ($item) use ($only) {
            foreach ($item as $key => $value) {
                if (!in_array($key, $only)) {
                    unset($item[$key]);
                }
            }
            return $item;
        }, $array);

        if ($is_collection) {
            return $result;
        }
        return $result[0];
    }

    /**
     *  Get for multi-dimensional arrays
     *
     *  @param array      An array to query on
     *  @param path|array A string representing the path to traverse.
     *                    Optionally pass as [ $path, ...$functions ] if `!$` is used
     *  @param bool       Collapse resulting data-set
     */
    static private function arrgh_get($array, $path, $collapse = false)
    {
        $path_string = $path;
        if (is_array($path)) {
            $path_string = array_shift($path);
        }
        $path_segments = explode(".", $path_string);
        $result = self::_arrgh_get_traverse($array, $path_segments, $collapse, /* functions */ $path );
        return $result;
    }

    static private function _arrgh_get_traverse($data, $path, $collapse = false, $functions = [])
    {
        $next_key      = array_shift($path);
        $plug_index    = is_numeric($next_key) ? (int) $next_key : null;
        $is_collection = self::isCollection($data);

        $next_node = null;

        // Apply custom function
        if ($next_key === '!$') {
            if ($is_collection) {
                $function  = array_shift($functions);
                $data      = array_values(array_filter($data, $function, ARRAY_FILTER_USE_BOTH));
                $next_key  = array_shift($path);
            } else {
                throw new Exception("Invalid path trying to invoke function on non-collection");
            }
        }

        // Select data either by index or key
        if ($plug_index !== null) {
            $count = count($data);
            if ($is_collection) {
                // Adjust negative index
                if ($plug_index < 0) {
                    $plug_index = $count === 1 ? 0 : $count + ($plug_index % $count);
                }

                // Plug data
                if (isset($data[$plug_index])) {
                    $next_node = $data[$plug_index];
                }
            } else {
                throw new Exception("Invalid path trying to plug item but data is not a collection");
            }
        } else {
            if ($next_key === null) {
                $next_node = $data;
            } else {
                if ($is_collection) {
                    $next_node = array_map(function ($item) use ($next_key) {
                        if ($item !== null && array_key_exists($next_key, $item)) {
                            return $item[$next_key];
                        }
                        return null;
                    }, $data);
                } else if (is_array($data)) {
                    if (array_key_exists($next_key, $data)) {
                        $next_node = $data[$next_key];
                    }
                }
            }
        }

        // If nothing matched break path and return
        if (empty($next_node)) {
            return null;
        }

        // If path is at the end return
        if (count($path) === 0) {
            if (is_array($next_node) && $collapse) {
                return array_filter($next_node);
            }
            return $next_node;
        }

        // If path is not completed
        if (is_array($next_node)) {

            // Recurse
            $node_is_collection = Arrgh::arrgh_is_collection($next_node);
            $node_depth = Arrgh::arrgh_depth($next_node);

            if ($node_is_collection) {
                // Collapse collections
                if ($collapse                  // if enabled
                    && !is_numeric($path[0])   // if next path segment is not an index
                    && $path[0] !== "!$"       // if not the result of a custom function
                    && $node_depth > 1         // if array of arrays
                ) {
                    $next_node = Arrgh::arrgh_collapse($next_node);
                }

                if (is_numeric($path[0]) && $node_depth <= 1) {
                    $result = self::_arrgh_get_traverse($next_node, $path, $collapse, $functions);
                } else {
                    // Collect data from sub-tree
                    $result = [];
                    foreach ($next_node as $node) {
                        if ($node === null) {
                            $result[] = null;
                        } else {
                            $partial = self::_arrgh_get_traverse($node, $path, $collapse, $functions);
                            if ($collapse) {
                                $result[] = $partial;
                            } else {
                                $result[] = [ $partial ];
                            }
                        }
                    }
                }

                // Since collection functions inject an array segment we must collapse the result
                if ($path[0] === "!$") {
                    $result = Arrgh::arrgh_collapse($result);
                }
            } else {
                $result = self::_arrgh_get_traverse($next_node, $path, $collapse, $functions);
            }
            if (is_array($result)) {
                // Collapse collections greater than 2
                if (Arrgh::arrgh_depth($result) > 2) {
                    $result = Arrgh::arrgh_collapse($result);
                }
                return array_filter($result);
            }
            return $result;
        }
        throw new Exception("Next node in path is not an array");
    }

    static private function arrgh_is_collection($mixed)
    {
        if (!is_array($mixed)) return false;
        $keys = array_keys($mixed);
        if (isset($keys[0]) && is_numeric($keys[0])) return true;
        return false;
    }

    static private function arrgh_depth($array)
    {
        $depth = 0;
        $child = $array; ;
        while(Arrgh::arrgh_is_collection($child)) {
            $depth += 1;
            $child = $child[0];
        }
        return $depth;
    }

    static private $arrgh_functions = [
        "map_ass",
        "sort_by",
        "collapse",
        "contains",
        "except",
        "only",
        'get',
        'is_collection',
        'depth',
    ];
    static private $simple_functions = [
        "array_change_key_case",
        "array_chunk",
        "array_column",
        "array_combine",
        "array_count_values",
        "array_diff",
        "array_diff_assoc",
        "array_diff_key",
        "array_diff_uassoc",
        "array_diff_ukey",
        "array_fill",
        "array_fill_keys",
        "array_filter",
        "array_flip",
        "array_intersect",
        "array_intersect_assoc",
        "array_intersect_key",
        "array_intersect_uassoc",
        "array_intersect_ukey",
        "array_keys",
        "array_merge",
        "array_merge_recursive",
        "array_pad",
        "array_product",
        "array_rand",
        "array_reduce",
        "array_replace",
        "array_replace_recursive",
        "array_reverse",
        "array_slice",
        "array_sum",
        "array_udiff",
        "array_udiff_assoc",
        "array_udiff_uassoc",
        "array_uintersect",
        "array_uintersect_assoc",
        "array_uintersect_uassoc",
        "array_unique",
        "array_values",
        "count",
        "max",
        "min",
        "range",
        "sizeof",
    ];
    static private $mutable_functions = [
        "array_push",
        "array_splice",
        "array_unshift",
        "array_walk",
        "array_walk_recursive",
        "arsort",
        "asort",
        "krsort",
        "ksort",
        "natcasesort",
        "natsort",
        "rsort",
        "shuffle",
        "sort",
        "uasort",
        "uksort",
        "usort",
    ];
    static private $mutable_functions_multiple = [
        "array_multisort",
    ];
    
    static private $mutable_value_functions = [
        "array_pop",
        "array_shift",
        "end",
    ];
    static private $reverse_functions = [
        "array_map",
    ];
    static private $swapped_functions = [
        "array_key_exists",
        "array_search",
        "implode",
        "in_array",
        "join",
    ];
    static private $starters = [
        "array_fill",
        "array_fill_keys",
        "range",
    ];
    static private $terminators = [
        "array_pop",
        "array_shift",
        "array_sum",
        "count",
        "join",
        "max",
        "min",
        "sizeof",
    ];
    static private $reverse_result_functions = [
        "uasort",
        "uksort",
        "usort",
        "asort",
        // "array_diff_uassoc",
        // "array_diff_ukey",
        // "array_intersect_uassoc",
        // "array_intersect_ukey",
    ];
}

if (defined("ARRGH")) {
    require dirname(__FILE__) . '/arrgh_functions.php';
}
