<?php

use Venom\Core\Config;
use Venom\Core\Database\Database;

$config = Config::getInstance();
$config->setVersion(1.0);
$config->setDatabase([
    Database::DB_TYPE => 'mysql', //please change only if you know what you're doing! this can break a lot.
    Database::DB_HOST => '127.0.0.1',
    Database::DB_PORT => '3306', //default port is 3306
    Database::DB_USER => 'venom',
    Database::DB_PASSWORD => 'venomPassword',
    Database::DB_DB => 'venomCMS',
    Database::DB_EXTRA => '' // optionals
]);

/**
 * Cron Mailing is something that will send only mails after a specific time like 1 min.
 * it is used to prevent spamming.
 * CronMailing looks if the Same Mail is in the Database in the last 24 Hours! if it's already in then it will skip the sending!
 */
$config->setMail([
    'useCron' => true, //if true it will not send mails directly.
    'writeToDB' => true, //is needed for cron and is always true if batch is use
    'host' => 'localhost',
    'port' => '587',
    'useTLS' => true, //use startTLS. is the default case ;) here it's important the security Cert is secure...
    'user' => 'youruser@yourdomain.de',
    'password' => 'this-is-secret',
    'from' => 'info@venom.io'
]);

$config->setSecurity([
    'useSecurity' => true, // should init the Security Module
    'securityClass' => Venom\Security\BaseLogin::class, // Security class that is used
    'secret' => 'venomDefaultSecret', // add to the hash.. ;) use HashingAlgo
    'algo' => 'SHA256' // SHA256, SHA512...,
]);

// all templates are in __DIR__/tpl/
// all themes are in __DIR__/public/theme/
$config->setRender([
    'theme' => 'default', //very important! it will search for a folder with this name.
    'assetDir' => 'default',
    'baseFile' => 'base', //this will called after all templates are rendered...
    'useCache' => false, //is only on big systems good
    'cacheName' => 'defaultCache', //this is for bigger systems, ignore it
    'uploadDir' => 'content/',
    'useStaticUrl' => false,
]);

$config->setEnableRouter(false);
$config->setMaintainMode(false);
$config->setDevMode(true);
$config->setBaseUrl(''); // can changed to something like that: https://example.com !not enter a / after the url! this will break all
$config->setSeoMode(true); //seo mode is to lookup the complete entered url in the database! without query-parameters and load the get parameters from it

$config->close();
