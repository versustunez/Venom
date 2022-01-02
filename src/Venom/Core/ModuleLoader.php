<?php


namespace Venom\Core;

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
            'Meta',
            'User',
            'Data',
            'Role',
            'SEO',
            'VenomStatus',
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
        $isAdmin = Config::getInstance()->isAdmin();
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
        foreach ($routes as $key => $route) {
            /** @var Route $route */
            $route->module = $module[Module::NAME];
            $route->isSecure = $module[Module::SECURE];
            $route->venom = $venom;
            $router->addRoute($key, $route);
        }
    }
}