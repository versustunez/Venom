<?php


namespace Venom\Entities;


use Venom\Core\Database\EasyQuery;
use Venom\Core\Database\Entity;

class User extends Entity
{
    public const ADMIN_ROLE = '-1';
    public const GUEST_ROLE = '0';

    public static string $tableName = "users";
    public string $username = "GUEST";
    public string $firstname = "";
    public string $lastname = "";
    public string $email = "";
    public string $password = "";
    public string $token = "";
    public string $salt = "";
    public int $roleId = 0;
    public bool $isActive = true;
    private ?RoleEntity $roleEntity = null;
    private bool $loaded = false;

    public function hasPermission(string $module, $type = RoleEntity::TYPE_WRITE): bool
    {
        if ($this->roleEntity === null) {
            $this->roleEntity = new RoleEntity();
            $this->roleEntity->id = $this->roleId;
            $this->roleEntity->load();
        }
        return $this->roleEntity->hasPermission($module, $type);
    }

    public function postLoad()
    {
        $this->loaded = true;
    }


    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function loadUser(): bool
    {
        $eq = new EasyQuery(User::$tableName, ["*"]);
        $eq->where("username", $this->username)
            ->where("id", $this->id, EasyQuery::WHERE_OR);
        $this->load([], $eq);
        return true;
    }
}
