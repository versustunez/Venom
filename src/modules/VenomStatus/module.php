<?php

use Modules\VenomStatus\Controller\VenomStatusController;
use Venom\Core\Module;
use Venom\Routing\Route;


$venom = $venom ?? die();
$venom->registerModule([
    Module::ACTIVE => true,
    Module::NAME => 'VenomStatusModule',
    Module::DESC => 'Show Routes and Modules!',
    Module::AUTHOR => 'VstZ dev',
    // NEED TO CHECK RIGHTS? :D IF FALSE WRITE IS ALWAYS ALLOWED ALSO READ!
    Module::SECURE => true,
    Module::ROUTE => [],
    Module::ADMIN_ROUTE => [
        '/venomStatus' => new Route(VenomStatusController::class, [
            "*" => [
                Route::GET => 'get'
            ]
        ])
    ],
    Module::TEMPLATE_PATH => __DIR__ . "/tpl/",
    Module::TEMPLATES => [

    ],
    Module::ADMIN_TEMPLATES => [

    ],
    Module::CONTROLLER => [

    ]
]);