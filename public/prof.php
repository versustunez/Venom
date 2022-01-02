<?php
// start profiling
tideways_xhprof_enable();
require_once "index.php";
$path = __DIR__ . "/../var/tmp/profiling/" . date("dmY-His") . "-" . uniqid() . ".profile";
var_dump($path);
file_put_contents($path, serialize(tideways_xhprof_disable()));