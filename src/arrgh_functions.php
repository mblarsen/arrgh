<?php
if (!defined("ARRGH_IS_DEFINED") || defined("ARRGH_REDEFINE")) {
    $arrgh_prefix = defined("ARRGH_PREFIX") ? ARRGH_PREFIX : "arrgh";
    
    if ($arrgh_prefix === "arrgh" || $arrgh_prefix === "arr") {
        require __DIR__ . "/prebuild.${arrgh_prefix}_functions.php";
    } else {
        if (in_array("eval", explode(",", ini_get("disable_functions")))) {
            throw new Exception("eval() must be activated to use other custom arrgh prefix");
        }

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

    // Define so it will not be defiend again
    if (!defined("ARRGH_IS_DEFINED")) define("ARRGH_IS_DEFINED", true);
}

