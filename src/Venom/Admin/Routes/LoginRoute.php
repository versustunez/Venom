<?php


namespace Venom\Admin\Routes;

use Venom\Routing\Route;
use Venom\Routing\Router;
use Venom\Security\Security;

class LoginRoute
{

    #[Route('/login', Route::POST, Router::ADMIN_ROUTER)]
    public function login(): bool
    {
        Security::get()->login();
        return true;
    }

    #[Route('/login', Route::GET, Router::ADMIN_ROUTER)]
    public function handle($fnc): bool
    {
        if ($fnc === 'logout') {
            Security::get()->logout();
            echo '{"reload": true}';
            die();
        }
        return true;
    }
}
