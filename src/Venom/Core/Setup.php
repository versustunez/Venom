<?php


namespace Venom\Core;


use Venom\Venom;

class Setup
{
    public static function loadConfig(bool $isAdmin): void
    {
        $config = Config::getInstance();
        $config->setIsAdmin($isAdmin);
        $file = self::tryLoading('config.inc.php', 'config.base.php', "Config");
        require $file;
    }

    public static function tryLoading(string $file, string $baseFile, string $type): string
    {
        $newFile = __DIR__ . '/../../../conf/' . $file;
        if (!file_exists($newFile)) {
            $newBaseFile = __DIR__ . '/../../../base/' . $baseFile;
            if (copy($newBaseFile, $newFile)) {
                echo 'Created File for: ' . $type . '! Please Adjust the file';
            } else {
                echo 'Cannot create File for: ' . $type . '!';
            }

            exit(1);
        }
        return $newFile;
    }

    public static function loadModules(Venom $venom): void
    {
        $file = self::tryLoading('modules.inc.php', 'module.base.php', "Modules");
        require $file;
        if (isset($modules)) {
            $venom->initModules($modules);
        }
    }

    public static function loadRouters(Venom $venom): void
    {
        $file = self::tryLoading('routers.inc.php', 'router.base.php', "Routers");
        require $file;
    }

    public static function loadLanguage(): void
    {
        $file = self::tryLoading('lang.inc.php', 'lang.base.php', "Languages");
        require $file;
    }
}