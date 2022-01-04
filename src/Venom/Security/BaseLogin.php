<?php


namespace Venom\Security;


use Venom\Core\ArgumentHandler;
use Venom\Core\Config;
use Venom\Entities\User;
use Venom\Helper\URLHelper;

/**
 * Class that Login stupid via Password, Username
 */
class BaseLogin implements Login
{

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function checkCredentials(): bool
    {
        $handler = ArgumentHandler::get();
        return $handler->hasPostItem('USERNAME') && $handler->hasPostItem('PASSWORD');
    }

    public function redirect(): void
    {
        $url = ArgumentHandler::get()->getPostItem('REDIRECT_TO', URLHelper::getInstance()->getUrl());
        if ($url === 'NO') {
            echo json_encode(['message' => 'login'], JSON_THROW_ON_ERROR);
        } else {
            header('Location: ' . $url);
        }
        die();
    }

    public function login(): bool
    {
        $sec = Config::get()->getSecurity();
        $this->user->username = (string)ArgumentHandler::get()->getPostItem('USERNAME');
        if (!$this->user->loadUser()) {
            return false;
        }
        $secret = $sec->secret ?? 'venom';
        $hashed = hash($sec->algo ?? 'SHA256', ArgumentHandler::get()->getPostItem('PASSWORD') . $secret . $this->user->salt);
        if ($this->user->password === $hashed) {
            $_SESSION['userID'] = $this->user->id;
            return true;
        }
        return false;
    }
}
