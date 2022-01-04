<?php


namespace Venom\Admin;


use Venom\Admin\Routes\LoginRoute;
use Venom\Core\Module;
use Venom\Core\ModuleLoader;
use Venom\Venom;

class AdminRouterInit
{
    public static function registerAdminModule(Venom $venom): void
    {
        ModuleLoader::initModule([
            Module::NAME => "AdminCoreUnsecure",
            Module::ACTIVE => true,
            Module::SECURE => false,
            Module::ADMIN_ROUTE => [
                LoginRoute::class
            ],
            Module::TEMPLATE_PATH => __DIR__ . "/tpl/",
            Module::CONTROLLER => []
        ], $venom);

        ModuleLoader::initModule([
            Module::NAME => "AdminCoreSecure",
            Module::ACTIVE => true,
            Module::SECURE => false,
            Module::ADMIN_ROUTE => [],
            Module::TEMPLATE_PATH => __DIR__ . "/tpl/",
            Module::CONTROLLER => []
        ], $venom);
    }
}
