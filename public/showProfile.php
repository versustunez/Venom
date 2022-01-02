<?php

function sortMe($a, $b): int
{
    return $b["wt"] <=> $a["wt"];
}

$path = __DIR__ . "/../var/tmp/profiling/";
$file = $_GET['FILE'] ?? null;
if (!$file) {
    $dir = scandir($path);
    echo "<ul>";
    foreach ($dir as $file) {
        if ($file === "." || $file === "..") {
            continue;
        } ?>
        <li><a href="/showProfile.php?FILE=<?= $file ?>"><?= $file ?></a></li>
        <?php
    }
    echo "</ul>";
} else {
    echo "<pre>";
    $data = unserialize(file_get_contents($path . $file));
    $data2 = uasort($data, "sortMe");
    var_dump($data);
    echo "</pre>";
}

