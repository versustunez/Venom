<?php

use Venom\Core\Config;
use Venom\Core\Setup;
use Venom\Helper\URLHelper;
use Venom\Venom;

require_once '../vendor/autoload.php';
session_start();
Setup::loadConfig(URLHelper::getInstance()->isAdminUrl());
Setup::loadLanguage();

$config = Config::get();
if ($config->isMaintenance()) {
    echo 'Currently not available';
    exit;
}
//if devMode is on show all errors!
if ($config->isDevMode()) {
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL);
}
$venom = Venom::get();
Setup::loadModules($venom);
$venom->inject();
