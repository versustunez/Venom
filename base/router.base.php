<?php
// file is included by Setup! so it can Render direct into VenomCore!
// this routes are only for API use!
// if you not need the Router disable it in the Config then the Default Seo-Loader will only used

use Venom\Routing\Route;
use Venom\Routing\Router;

if (!isset($venom)) {
    echo 'make sure Venom is loaded!';
    exit(1);
}

$router = new Router(Router::DEFAULT_ROUTER, 1.0, 'api/');
$venom->addRouter($router);