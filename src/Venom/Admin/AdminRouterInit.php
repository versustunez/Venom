<?php


namespace Venom\Admin;


use Venom\Admin\Routes\LoginRoute;
use Venom\Admin\Routes\TemplateLoader;
use Venom\Routing\Route;
use Venom\Routing\Router;
use Venom\Venom;

class AdminRouterInit
{
    public static function registerAdminRouters(Venom $venom): void
    {
        $venom->getRouter(Router::ADMIN_ROUTER)->addRoutes(self::getRoutes());
    }

    public static function getRoutes(): array
    {
        return [
            '/login' => new Route(LoginRoute::class, [
                '*' => [
                    "POST" => 'login'
                ],
                '1' => [
                    "GET" => 'handle'
                ]
            ]),
            '/templateLoader' => new Route(TemplateLoader::class, [
                '*' => [
                    "GET" => 'handle'
                ],
            ]),
        ];
    }
}