<?php


namespace Venom\Entities;

use Venom\Core\Database\EasyQuery;
use Venom\Core\Database\Entity;

class RoleEntity extends Entity
{
    public static string $tableName = "roles";
    public string $name = "";
    public string $content = "";
    public bool $isActive = true;
    private array $roles = [];

    public const TYPE_WRITE = 1;
    public const TYPE_READ = 0;
    public const TYPE_NO = -1;


    public function hasPermission(string $module, int $type): bool
    {
        if ($this->id === -1) {
            return true;
        }
        if ($type === self::TYPE_NO) {
            return true;
        }
        if (!isset($this->roles[$module]) && $type) {
            return false;
        }
        $mod = $this->roles[$module];
        return $mod["type"] === $type;
    }


    public function postLoad()
    {
        if (!empty($this->content)) {
            $this->roles = json_decode($this->content);
        }
    }

    public function preSave()
    {
        $this->content = json_encode($this->roles);
    }

    public function load($fields = ['*'], ?EasyQuery $query = null): static
    {
        if ($this->id === -1 || $this->id === 0) {
            return $this;
        }
        return parent::load($fields, $query);
    }


}