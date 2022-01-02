<?php

namespace Venom\Core\Database;

class DatabaseHandler
{
    private static ?DatabaseHandler $instance = null;
    private Database $db;
    private array $cache; //EntityManager Cache!

    protected function __construct()
    {
        $this->db = new Database();
    }

    public static function get(): Database
    {
        return self::getInstance()->db;
    }

    public static function getInstance(): DatabaseHandler
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseHandler();
        }
        return self::$instance;
    }

    public static function getEntityManager($entityClass): EntityManager
    {
        $instance = self::getInstance();
        // i dont make sure this class exist because the user should do this ;)
        if (!isset($instance->cache[$entityClass])) {
            $instance->cache[$entityClass] = new EntityManager($entityClass, self::get());
        }
        return $instance->cache[$entityClass];
    }

    public static function createEntityManager($entityClass) : EntityManager
    {
        return new EntityManager($entityClass, self::get());
    }
}
