<?php

use Modules\SEO\Controller\SeoUrlController;
use Venom\Core\Module;
use Venom\Routing\Route;


$venom = $venom ?? die();
$venom->registerModule([
    Module::ACTIVE => true,
    Module::NAME => 'SeoModule',
    Module::DESC => 'SEO Management for beautiful URLs',
    Module::AUTHOR => 'VstZ dev',
    // NEED TO CHECK RIGHTS? :D IF FALSE WRITE IS ALWAYS ALLOWED ALSO READ!
    Module::SECURE => true,
    Module::ROUTE => [],
    Module::ADMIN_ROUTE => [
        '/seoUrl' => new Route(SeoUrlController::class, [
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

    ],
    Module::ADMIN_TEMPLATES => [

    ],
    Module::CONTROLLER => [

    ]
]);