<?php

require __DIR__ . '/src/Arrgh.php';
$arrgh_prefix = isset($argv[1]) ? $argv[1] : "arrgh";
$prefix = $arrgh_prefix . "_";
$all_functions = array_merge(...array_values(Arrgh::allFunctions()));

echo "<?php\nfunction $arrgh_prefix(\$array = []) {
    return new Arrgh(\$array);
}\n";

foreach ($all_functions as $function) {
    if (strpos($function, "array_") === 0) {
        $function = substr($function, strlen("array_"));
    }
    $function_name = $prefix . $function;
    $function_impl = "function $function_name()\n{
    return Arrgh::$function(...func_get_args());
}\n";
    echo $function_impl;
}
