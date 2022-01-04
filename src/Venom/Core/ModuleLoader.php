<?php


namespace Venom\Core;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Venom\Helper\TemplateUtil;
use Venom\Routing\Route;
use Venom\Routing\Router;
use Venom\Venom;

class ModuleLoader
{
    public static function getModules(): array
    {
        return [
            'User',
        ];
    }

    public static function loadModule(string $name, Venom $venom)
    {
        // load module search in the Module Path for a module.php file
        $dir = __DIR__ . "/../../modules/" . $name . "/module.php";
        if (!file_exists($dir)) {
            throw new RuntimeException("Module File: \"$dir\" Not found");
        }
        include_once $dir;
    }

    public static function initModule(array $module, Venom $venom): bool
    {
        if (!$module[Module::ACTIVE]) {
            return false;
        }
        // register Router, Templates and more :)
        $isAdmin = Config::get()->isAdmin();
        if ($isAdmin) {
            self::registerRoutes($module,
                $venom,
                $module[Module::ADMIN_ROUTE],
                $venom->getRouter(Router::ADMIN_ROUTER)
            );
            TemplateUtil::getInstance()->addTemplates($module[Module::ADMIN_TEMPLATES], $module[Module::TEMPLATE_PATH]);
        } else {
            self::registerRoutes($module,
                $venom,
                $module[Module::ROUTE],
                $venom->getRouter(Router::DEFAULT_ROUTER)
            );
            TemplateUtil::getInstance()->addTemplates($module[Module::TEMPLATES], $module[Module::TEMPLATE_PATH]);
        }
        $venom->addControllers($module[Module::CONTROLLER]);
        return true;
    }

    public static function registerRoutes(array $module, Venom $venom, array $routes, Router $router)
    {
        $cacheKey = $module[Module::NAME] . "__route__cache.cache";
        $cache = CacheHandler::get($cacheKey) ?? [];
        $changed = false;
        foreach ($routes as $route) {
            if (isset($cache[$route])) {
                self::createFromCache($route, $cache, $module, $venom);
            } else {
                try {
                    self::createFromReflection($route, $cache, $module, $venom);
                    $changed = true;
                } catch (ReflectionException $e) {
                    trigger_error("Error in Reflection");
                    continue;
                }
            }
        }
        if ($changed) {
            CacheHandler::put($cacheKey, $cache);
        }
    }

    private static function createFromCache(string $route, array &$cacheData, array $module, Venom $venom): void
    {
        $data = $cacheData[$route];
        if ($data === 'from-function') {
            $createdRoute = Route::create($route, []);
            if ($createdRoute !== null) {
                $createdRoute->isSecure = $module[Module::SECURE];
                $createdRoute->module = $module[Module::NAME];
                $createdRoute->venom = $venom;
            }
        } else {
            foreach ($data as $method => $routeData) {
                $createdRoute = Route::create($route, $routeData['args'], $routeData['parameters'], $method);
                $createdRoute->isSecure = $module[Module::SECURE];
                $createdRoute->module = $module[Module::NAME];
                $createdRoute->venom = $venom;
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private static function createFromReflection(string $route, array &$cacheData, array $module, Venom $venom): void
    {
        $class = new ReflectionClass($route);
        $classAttributes = $class->getAttributes(Route::class);
        if (count($classAttributes) > 0) {
            $route = Route::create($route, []);
            $cacheData[$route] = 'from-function';
        } else {
            $methods = $class->getMethods();
            foreach ($methods as $method) {
                $attributes = $method->getAttributes(Route::class);
                if (count($attributes) === 0) continue;
                $attribute = $attributes[0];
                $createdRoute = Route::create($route, $attribute->getArguments(), count($method->getParameters()), $method->getShortName());
                $cacheData[$route][$method->getShortName()] = [
                    'args' => $attribute->getArguments(),
                    'parameters' => count($method->getParameters())
                ];
                $createdRoute->isSecure = $module[Module::SECURE];
                $createdRoute->module = $module[Module::NAME];
                $createdRoute->venom = $venom;
            }
        }
    }
}
