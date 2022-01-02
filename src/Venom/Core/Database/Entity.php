<?php

namespace Venom\Core\Database;

// Entity has a Load and Save function!
// The Entity needs to have a primary key... most of the time this is a id!
use JsonSerializable;
use RuntimeException;

abstract class Entity implements JsonSerializable
{
    public static string $tableName = "";
    // make sure this exists!
    public int $id = -1;
    public string $primaryKey = "id";
    public array $loadedFields = [];
    public array $blackList = [];
    // Please override this Property in the Class you implement the Abstract Class! this is needed to run the right SQL calls
    public ?array $fields = null;

    // Override this if you want special fields :)

    public function getFieldsToWrite(): array
    {
        if ($this->fields !== null) {
            return $this->fields;
        }
        $localBlacklist = array_merge(["primaryKey", "tableName", "loadedFields", "blackList", "fields"], $this->blackList);
        $allLoaded = in_array("*", $this->loadedFields);
        $vars = get_object_vars($this);
        foreach ($vars as $key => $var) {
            if (in_array($key, $localBlacklist)) {
                unset($vars[$key]);
            }
        }
        if (!$allLoaded) {
            foreach ($vars as $key => $var) {
                if (!in_array($key, $this->loadedFields)) {
                    unset($vars[$key]);
                }
            }
        }
        //unset($vars[$this->primaryKey]);
        $this->fields = $vars;
        return $this->fields;
    }

    public function save(): bool
    {
        $this->preSave();
        $primaryKey = $this->primaryKey;
        $fields = $this->removeEmptyFields($this->getFieldsToWrite());
        $query = new EasyQuery(static::$tableName);
        foreach ($fields as $key => $field) {
            $query->addArgAndField($key, $field);
        }
        if ($this->$primaryKey === "") {
            $query->buildInsertQuery();
        } else {
            $query->where($primaryKey, $this->$primaryKey)->buildUpdateQuery();
        }
        return DatabaseHandler::get()->execute($query);
    }

    public function load($fields = ['*'], ?EasyQuery $query = null): static
    {
        if ($query === null) {
            $primaryKey = $this->primaryKey;
            $query = new EasyQuery(static::$tableName, $fields);
            $query->where($primaryKey, $this->$primaryKey)->setLimit(1)->buildSelect();
        } else {
            $query->setLimit(1)->buildSelect();
        }

        $item = DatabaseHandler::get()->getOne($query);
        if ($item === null) {
            return $this;
        }
        $lazy = $item->getData();
        $this->id = $item->id;
        foreach ($lazy as $key => $item) {
            $this->$key = $item;
        }
        $this->fields = $fields;
        if (!in_array('*', $query->getFields())) {
            $this->loadedFields = array_merge($this->loadedFields, $query->getFields());
        } else {
            $this->loadedFields = array_merge($this->loadedFields, array_keys($lazy));
        }

        $this->postLoad();
        return $this;
    }

    public function __set($name, $value)
    {
        // Implement your own if you want to override this behaviour!
        throw new RuntimeException("Write to Property: $name that is not Available in the Entity!");
    }

    public function delete()
    {
        $key = $this->primaryKey;
        $query = new EasyQuery(self::$tableName);
        $query->setArg($this->primaryKey, $this->$key)->buildDeleteQuery();
        DatabaseHandler::get()->execute($query->getQuery(), $query->getArgs());
    }

    public function jsonSerialize(): array
    {
        return $this->getFieldsToWrite();
    }

    public function preSave()
    {
    }

    public function postLoad()
    {
    }

    private function removeEmptyFields(array $vars): array
    {
        foreach ($vars as $name => $item) {
            if (empty($item) && $name != $this->primaryKey) {
                unset($vars[$name]);
            }
        }
        return $vars;
    }

    public function getFields(): array
    {
        return $this->loadedFields;
    }
}
