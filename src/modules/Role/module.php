<?php
use Venom\Core\Module;


$venom = $venom ?? die();
$venom->registerModule([
    Module::ACTIVE => true,
    Module::NAME => 'RoleModule',
    Module::DESC => 'Role Management',
    Module::AUTHOR => 'VstZ dev',
    Module::SECURE => true,
    Module::ROUTE => [],
    Module::ADMIN_ROUTE => [],
    Module::TEMPLATE_PATH => __DIR__ . "/tpl/",
    Module::TEMPLATES => [],
    Module::ADMIN_TEMPLATES => [],
    Module::CONTROLLER => []
]);
