<?php


namespace Venom\Admin\Routes;


use Venom\Security\Security;

class LoginRoute
{

    public function login(): bool
    {
        Security::get()->login();
        return true;
    }

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