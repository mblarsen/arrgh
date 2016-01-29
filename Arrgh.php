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

if (defined("ARRGH") && ARRGH === true) {
    $arrgh_prefix = defined("ARRGH_PREFIX") ? ARRGH_PREFIX : "arrgh";
    $prefix = $arrgh_prefix . "_";
    $all_functions = array_merge(...array_values(Arrgh::allFunctions()));

    eval("function $arrgh_prefix(\$array = []) {
        return new Arrgh(\$array);
    }");

    foreach ($all_functions as $function) {
        if (strpos($function, "array_") === 0) {
            $function = substr($function, strlen("array_"));
        }
        $function_name = $prefix . $function;
        $function_impl = "function $function_name () {
            return Arrgh::$function(...func_get_args());
        }";
        eval($function_impl);
    }
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
            "_call"         => self::$simple_functions,
            "_shiftPush"    => self::$reverse_functions,
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

        if ($object && !in_array($matching_function, self::$starters)) {
            array_unshift($args, $object->array);
        }

        $result = self::$matching_handler($matching_function, $args);

        if ($result) {
            if ($object) {
                $object->array = $result;
                if (in_array($matching_function, self::$terminators)) {
                    return $result;
                }
                return $object;
            }
            return $result;
        }

        throw new InvalidArgumentException("Method {$matching_function} doesn't exist");
    }

    /* Calls the native function directly */
    static private function _call($function, $args)
    {
        return $function(...$args);
    }

    /* Shifts of the first argument (callable) and pushes it to the end */
    static private function _shiftPush($function, $args)
    {
        $first_argument = array_shift($args);
        array_push($args, $first_argument);
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
        // TODO throw exception
        return $array;
    }

    /* Makes a copy of the array and returns it after invoking function */
    static private function _copyValue($function, $args)
    {
        $array = array_shift($args);
        $result = $function($array, ...$args);
        // TODO throw exception
        return $result;
    }

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