<?php

/**
 * Put the following in your init code to enable arrgh functions:
 *
 * define("ARRGH", true);
 *
 * If you don't fancy the `arrgh` prefix in e.g. `arrgh_map` you can change it
 * to something less piraty like:
 *
 * define("ARRGH_PREFIX", "arr");
 *
 * Then `arrgh_map` becomes `arr_map`
 *
 */

if (defined("ARRGH")) {
    require dirname(__FILE__) . '/arrgh_functions.php';
}

/**
 * A chainable array API or a set of static functions, or both.
 */
class Arrgh
{
    private $array;

    /* Creates a new arrgh array */
    public function __construct($array = [])
    {
        $this->array = $array;
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
            "_copyValue"    => self::$mutable_value_functions,
        ];
    }

    /* Transforms the incoming calls to native calls */
    static private function invoke($method, $args, $object = null)
    {
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

        // If chain unshift array onto argument stack
        if ($object && !in_array($matching_function, self::$starters)) {
            array_unshift($args, $object->array);
        }

        $result = self::$matching_handler($matching_function, $args);

        if ($object) {
            $object->array = $result;
            if (in_array($matching_function, self::$terminators)) {
                return $result;
            }
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

    /* Makes a copy of the array and returns it after invoking function */
    static private function _copyValue($function, $args)
    {
        $array = array_shift($args);
        $result = $function($array, ...$args);
        return $result;
    }

    static private function _arrgh($function, $args)
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
            usort($array, $key);
            if ($direction === -1) {
                $array = array_reverse($array);
            }
        } else {
            $compare_function = "strcmp";
            if (array_key_exists($key, $array[0]) && is_numeric($array[0][$key]) &&
                array_key_exists($key, $array[1]) && is_numeric($array[1][$key])) {
                $compare_function = null;
            }

            $usort_function = function ($a, $b) use ($compare_function, $key, $direction_int) {
                $a_isset = isset($a[$key]);
                $b_isset = isset($b[$key]);
                if (!$a_isset || !$b_isset) {
                    if (!$a_isset && !$b_isset) return 1;
                    return $direction_int * ($a_isset && !$b_isset ? 1 : -1);
                }
                if ($compare_function === null) {
                    return $direction_int * ($a[$key] - $b[$key]);
                }
                return  $compare_function($a[$key], $b[$key]) * $direction_int;
            };

            usort($array, $usort_function);
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

    static private function arrgh_get($array, $path, $collapse = false)
    {
        $path_string = $path;
        if (is_array($path)) {
            $path_string = array_shift($path);
        }
        $path_segments = explode(".", $path_string);
        $result = self::_arrgh_get_traverse($array, $path_segments, $collapse, /* functions */ $path );
        // Re-index collection
        // if (Arrgh::arrgh_is_collection($result)) {
            // return array_values($result);
        // }
        return $result;
    }

    static private function _arrgh_get_traverse($data, $path, $collapse = false, $functions = [])
    {
        $next_key      = array_shift($path);
        $plug_index    = is_numeric($next_key) ? (int) $next_key : null;
        $is_collection = self::isCollection($data);

        $next_node = null;

        if (in_array($next_key, ['!$'])) {
            if ($is_collection && $next_key === '!$') {
                $function  = array_shift($functions);
                $data      = array_values(array_filter($data, $function, ARRAY_FILTER_USE_BOTH));
                $next_key  = array_shift($path);
            } else {
                throw new Exception("Invalid path trying to invoke function on non-collection");
            }
        }
        if ($plug_index !== null || in_array($next_key, ["!>"])) {
            if ($is_collection) {
                if ($plug_index !== null && isset($data[$plug_index])) {
                    $next_node = $data[$plug_index];
                } else if ($next_key === '!>') {
                    $next_node = array_pop($data);
                }
            } else {
                throw new Exception("Invalid path trying to plug item but data is not a collection");
            }
        } else {
            if ($next_key !== null) {
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
            } else {
                $next_node = $data;
            }
        }

        if ($next_node === null) {
            return null;
        }

        if (count($path) === 0) {
            if (is_array($next_node) && $collapse) {
                return array_filter($next_node);
            }
            return $next_node;
        }

        // If path is not completed
        if (is_array($next_node)) {
            if (empty($next_node)) {
                return $next_node;
            }

            // Recurse
            $array_keys = array_keys($next_node);
            if (Arrgh::arrgh_is_collection($next_node)) {
                if ($collapse
                    && !is_numeric($path[0])
                    && !in_array($path[0], ["!>", "!$"])
                ) {
                    $next_node = Arrgh::arrgh_collapse($next_node);
                }
                $sub_result = [];
                foreach ($next_node as $node) {
                    if ($node === null) {
                        $sub_result[] = null;
                    } else {
                        $sub_result[] = self::_arrgh_get_traverse($node, $path, $collapse, $functions);
                    }
                }

                // Since collection functions inject an array segment we must collapse the result
                if (in_array($path[0], ["!$"])) {
                    $sub_result = Arrgh::arrgh_collapse($sub_result);
                }
            } else {
                $sub_result = self::_arrgh_get_traverse($next_node, $path, $collapse, $functions);
            }
            if (is_array($sub_result)) {
                return array_filter($sub_result);
            }
            return $sub_result;
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

    static private $arrgh_functions = [
        "map_ass",
        "sort_by",
        "collapse",
        "contains",
        "except",
        "only",
        'get',
        'is_collection',
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
        "range",
        "sizeof",
    ];

    static private $mutable_functions = [
        "array_multisort",
        "array_push",
        "array_splice",
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
    static private $mutable_value_functions = [
        "array_pop",
        "end",
    ];
    static private $reverse_functions = [
        "array_map",
    ];
    static private $swapped_functions = [
        "array_key_exists",
        "array_search",
        "implode",
        "in_​array",
        "join",
    ];
    static private $starters = [
        "array_fill",
        "array_fill_keys",
        "range",
    ];
    static private $terminators = [
        "array_pop",
        "array_sum",
        "count",
        "join",
        "sizeof",
    ];
}