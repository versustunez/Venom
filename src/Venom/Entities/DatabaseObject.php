<?php


namespace Venom\Entities;

use Venom\Core\Database\Entity;

/**
 * Database Object to use queries like this $obj->id, $obj->value
 * also the option to print it in csv format ; as delimiter
 * @package Venom\Database
 */
class DatabaseObject extends Entity
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

    public function __isset($name): bool
    {
        return isset($this->data[$name]);
    }

    public function toString(): string
    {
        return implode(';', $this->data);
    }

    public function getHead(): string
    {
        $keys = array_keys($this->data);
        return implode(';', $keys);
    }

    public function getData(): array
    {
        return $this->data;
    }
}