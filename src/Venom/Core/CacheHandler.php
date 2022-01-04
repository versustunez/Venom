<?php

namespace Venom\Core;

class CacheHandler
{

    static string $cacheDir = __DIR__ . '/../../../var/cache/';

    public static function get(string $param)
    {
        $file = self::$cacheDir . $param;
        if (file_exists($file)) {
            return unserialize(file_get_contents($file));
        }
        return null;
    }

    public static function put(string $param, $data)
    {
        $file = self::$cacheDir . $param;
        file_put_contents($file, serialize($data));
    }

}
