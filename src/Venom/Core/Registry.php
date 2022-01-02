<?php


namespace Venom\Core;

use Venom\Controller\SeoController;

/**
 * Singleton Class... hold current URL => can
 * @package Venom
 */
class Registry
{
    private static ?Registry $instance = null;
    private SeoController $seo;
    private Language $lang;

    private function __construct()
    {
        $this->seo = new SeoController();
        $this->lang = new Language();
    }

    public static function getInstance(): Registry
    {
        if (self::$instance === null) {
            self::$instance = new Registry();
        }
        return self::$instance;
    }

    public function getSeo(): SeoController
    {
        return $this->seo;
    }

    public function getLang(): Language
    {
        return $this->lang;
    }
}