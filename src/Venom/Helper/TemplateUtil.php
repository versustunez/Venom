<?php


namespace Venom\Helper;


use Venom\Core\Config;

class TemplateUtil
{
    private static ?TemplateUtil $instance = null;
    private string $baseTemplate;
    private string $templateDir;
    private array $templates = [];

    private function __construct()
    {
        if (Config::getInstance()->isAdmin()) {
            $base = 'base';
            $theme = 'admin';
        } else {
            $data = Config::getInstance()->getRenderer();
            $theme = $data->theme;
            $base = $data->baseFile ?? 'base';
        }
        $this->baseTemplate = $base . '.php';
        $this->templateDir = __DIR__ . '/../../../tpl/' . $theme . '/';
    }

    public static function getInstance(): TemplateUtil
    {
        if (self::$instance === null) {
            self::$instance = new TemplateUtil();
        }
        return self::$instance;
    }

    public function getDir(): string
    {
        return $this->templateDir;
    }

    public function getBase(): string
    {
        return $this->baseTemplate;
    }

    public function addTemplates($templates, string $basePath)
    {
        foreach ($templates as $key => $template) {
            $this->templates[$key] = $basePath . $template;
        }
    }

    public static function includeTemplate($template, $suffix = '.php'): bool|string
    {
        $tx = self::getInstance()->getCache($template);
        if ($tx === "") {
            $dir = self::getInstance()->getDir();
            $tx = $dir . $template;
        }
        $tx .= $suffix;
        if (file_exists($tx)) {
            ob_start();
            include_once $tx;
            return ob_get_clean();
        }
        return '';
    }

    private function getCache($template)
    {
        return $this->templates[$template] ?? '';
    }
}