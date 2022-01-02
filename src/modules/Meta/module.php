<?php

use Modules\Meta\Controller\MetaAPIController;
use Venom\Core\Module;
use Venom\Routing\Route;


$venom = $venom ?? die();
$venom->registerModule([
    Module::ACTIVE => true,
    Module::NAME => 'MetaModule',
    Module::DESC => 'Meta Data Module for SEO',
    Module::AUTHOR => 'VstZ dev',
    // NEED TO CHECK RIGHTS? :D IF FALSE WRITE IS ALWAYS ALLOWED ALSO READ!
    Module::SECURE => true,
    Module::ROUTE => [],
    Module::ADMIN_ROUTE => [
        '/metaData' => new Route(MetaAPIController::class, [
            "*" => [
                Route::GET => 'get'
            ],
            "1" => [
                Route::GET => 'getById',
                Route::POST => 'update',
                Route::PUT => 'insert',
                Route::DELETE => 'delete'
            ]
        ])
    ],
    Module::TEMPLATE_PATH => __DIR__ . "/tpl/",
    Module::TEMPLATES => [
        // Include Templates with shorter names! $render->include("meta_roles")
        //'meta_roles' => 'PATH_TO_TEMPLATE_FROM_TEMPLATE_PATH'
    ],
    Module::ADMIN_TEMPLATES => [
        //
    ],
    Module::CONTROLLER => [

    ]
]);