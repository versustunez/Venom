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

        $security = Security::get();
        $isLogin = $security->hasPermission("admin", RoleEntity::TYPE_READ);
        $renderer->addVar('current.user', $security->getUsername());
        $renderer->addVar('isLoggedIn', $isLogin);

        return true;
    }

    public function getTemplate(): string
    {
        return $this->tpl;
    }
}
