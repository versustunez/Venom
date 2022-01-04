<?php


namespace Venom\Views;


use Venom\Core\ArgumentHandler;
use Venom\Core\Config;
use Venom\Helper\MetaGenerator;
use Venom\Helper\TemplateUtil;
use Venom\Venom;

class VenomRenderer
{
    private Venom $venom;
    private ?RenderController $controller;
    private ?MetaGenerator $metaGenerator;
    private string $templateData = '';
    private array $vars = [];
    private string $baseTemplate = '';
    private string $templateDir = '';

    public function __construct(Venom $venom)
    {
        $this->venom = $venom;
    }

    public function render(): void
    {
        $isAsync = false;
        if ($this->controller) {
            ob_start();
            $this->controller->render($this);
            $this->templateData = ob_get_clean();
            $isAsync = $this->controller->getTemplate() === 'async';
        }
        if ($isAsync || ArgumentHandler::get()->getItem('async', 'false') === 'true') {
            echo $this->templateData;
            exit;
        }
        $this->loadBasicTemplate();
    }

    public function loadBasicTemplate(): void
    {
        if (file_exists($this->templateDir . $this->baseTemplate)) {
            include_once $this->templateDir . $this->baseTemplate;
        } else {
            echo 'Base Template not found...';
        }
    }

    public function renderTemplate($template): void
    {
        // random variable name... to remove it instantly
        echo TemplateUtil::includeTemplate($template);
    }

    /**
     * function will load a template (without extension!) into a variable and return the content
     * @param $template
     * @param string $varName
     * @return false|string
     */
    public function includeTemplate($template, string $varName = ''): bool|string
    {
        $data = TemplateUtil::includeTemplate($template);
        $this->vars[$varName] = $data;
        return $data;
    }

    public function addVar($name, $value): void
    {
        $this->vars[$name] = $value;
    }

    public function getVar($name)
    {
        return $this->vars[$name];
    }

    public function deleteVar($name): void
    {
        unset($this->vars[$name]);
    }

    public function init(?RenderController $controller): void
    {
        $this->controller = $controller;
        if (!Config::get()->isAdmin()) {
            $this->metaGenerator = new MetaGenerator();
            $this->metaGenerator->loadById();
        }
        $util = TemplateUtil::getInstance();
        $this->templateDir = $util->getDir();
        $this->baseTemplate = $util->getBase();
    }
}
