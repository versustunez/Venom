<?php

use Modules\User\Controller\UserAPIController;
use Venom\Core\Module;
use Venom\Routing\Route;
use Venom\Venom;


/** @var Venom $venom */
$venom = $venom ?? die();
$venom->registerModule([
    Module::ACTIVE => true,
    Module::NAME => 'UserModule',
    Module::DESC => 'User Management',
    Module::AUTHOR => 'VstZ dev',
    // NEED TO CHECK RIGHTS? :D IF FALSE WRITE IS ALWAYS ALLOWED ALSO READ!
    Module::SECURE => true,
    Module::ROUTE => [],
    Module::ADMIN_ROUTE => [],
    Module::TEMPLATE_PATH => __DIR__ . "/tpl/",
    Module::TEMPLATES => [
    ],
    Module::ADMIN_TEMPLATES => [
        //
    ],
    Module::CONTROLLER => [

    ]
]);
