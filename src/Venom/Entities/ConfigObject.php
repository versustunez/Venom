<?php


namespace Venom\Entities;


use Venom\Core\Database\Entity;

class ConfigObject extends Entity
{
    private array $data = [];

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function toString(): string
    {
        return implode(';', $this->data);
    }
}