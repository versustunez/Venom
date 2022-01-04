<?php


namespace Venom\Security;

use RuntimeException;
use Venom\Core\Config;
use Venom\Entities\RoleEntity;
use Venom\Entities\User;

class Security
{
    private static ?Security $instance = null;
    private ?User $user;

    public function __construct()
    {
        $this->user = new User();
        $this->user->id = $_SESSION['userID'] ?? "-1";
        $this->user->load();
    }

    public static function get(): Security
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function hasPermission(string $module, $type = RoleEntity::TYPE_WRITE): bool
    {
        return $this->user->hasPermission($module, $type);
    }

    public function login(): void
    {
        if ($this->user->isLoaded()) {
            throw new RuntimeException('Try to re-login!');
        }
        $sec = Config::get()->getSecurity();
        $login = new $sec->securityClass($this->user);
        if ($login instanceof Login) {
            if (!$login->checkCredentials() || !$login->login()) {
                http_response_code(401);
            }
            $login->redirect();
        }
    }

    public function logout(): void
    {
        unset($_SESSION['userID']);
        $this->user = new User();
    }

    public function getUsername(): string
    {
        return $this->user->username;
    }
}
