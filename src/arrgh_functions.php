<?php
if (!defined("ARRGH_IS_DEFINED")) {
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

    // Define so it will not be defiend again
    define("ARRGH_IS_DEFINED", true);
}

