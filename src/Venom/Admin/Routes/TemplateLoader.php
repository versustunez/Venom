<?php


namespace Venom\Admin\Routes;

use Venom\Core\ArgumentHandler;
use Venom\Core\Config;
use Venom\Helper\TemplateUtil;

class TemplateLoader
{
    public function handle(): bool
    {
        if (!Config::getInstance()->isDevMode()) {
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60 * 60 * 30)));
            header('Cache-Control: public');
        }
        $id = ArgumentHandler::get()->getItem('tpl', '..');
        if (strpos($id, '..')) {
            return false;
        }
        echo TemplateUtil::includeTemplate('jsTemplates/' . $id, '.tpl');
        die();
    }
}