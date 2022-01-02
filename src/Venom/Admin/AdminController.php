<?php


namespace Venom\Admin;


use Venom\Entities\RoleEntity;
use Venom\Helper\URLHelper;
use Venom\Security\Security;
use Venom\Views\Asset;
use Venom\Views\RenderController;
use Venom\Views\VenomRenderer;

class AdminController implements RenderController

{

    private string $tpl = 'default';

    public function register(): bool
    {
        return true;
    }

    public function render(VenomRenderer $renderer): bool
    {
        if (!in_array(URLHelper::getInstance()->getUrl(), ['/admin/', '/admin'])) {
            http_response_code(404);
            $this->tpl = 'async';
        }

        $isLogin = Security::get()->hasPermission("admin", RoleEntity::TYPE_READ);
        $renderer->addVar('isLoggedIn', $isLogin);
        if (!$isLogin) {
            Asset::get()->addCSS('login', 'login.css');
        } else {
            Asset::get()->addCSS('admin', 'admin-panel.css');
        }
        Asset::get()->addCSS('styles', 'style.css', 1);
        Asset::get()->addJS('scripts', 'scripts.min.js', 1);
        // Components are the Rendering-Pipeline to know how each Admin-Component needs to be rendered
        Asset::get()->addJS('components', 'components.min.js', 5);

        return true;
    }

    public function getTemplate(): string
    {
        return $this->tpl;
    }
}