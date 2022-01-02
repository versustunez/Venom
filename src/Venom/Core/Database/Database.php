<?php

namespace Venom\Core\Database;
// class that hold the Database Connection! and Executes like the DatabaseHandler
use PDO;
use PDOException;
use PDOStatement;
use Venom\Entities\DatabaseObject;

class Database
{
    // constants
    public const DB_TYPE = 'type';
    public const DB_HOST = 'host';
    public const DB_PORT = 'port';
    public const DB_USER = 'user';
    public const DB_PASSWORD = 'pw';
    public const DB_DB = 'db';
    public const DB_EXTRA = 'extra';
    private ?PDO $db = null;

    public function init(array $data): void
    {
        //init instance with the current data... only working if the db is not init!
        if ($this->db != null) {
            return;
        }
        $dsn = '%s:host=%s;dbname=%s;port=%s';
        $connectString = sprintf($dsn, $data[self::DB_TYPE], $data[self::DB_HOST], $data[self::DB_DB], $data[self::DB_PORT]);
        if (!empty($data[self::DB_EXTRA])) {
            $connectString .= ';' . $data[self::DB_EXTRA];
        }
        try {
            $this->db = new PDO($connectString, $data[self::DB_USER], $data[self::DB_PASSWORD]);
        } catch (PDOException $e) {
            echo "<h1>Critical Database Exception</h1>";
            trigger_error($e->getMessage());
            die($e->getCode());
        }
    }

    public function getOne(string|EasyQuery $query, array $args = []): ?DatabaseObject
    {
        $sql = $query;
        if ($query instanceof EasyQuery) {
            $sql = $query->getQuery();
            $args = $query->getArgs();
        }
        $data = $this->getAll($sql, $args);
        if (count($data) > 0) {
            return $data[0];
        }
        return null;
    }

    public function getAll(string|EasyQuery $query, array $args = []): array
    {
        $sql = $query;
        if ($query instanceof EasyQuery) {
            $sql = $query->getQuery();
            $args = $query->getArgs();
        }
        $stmt = $this->db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, DatabaseObject::class);
        $stmt->execute($args);
        return $stmt->fetchAll();
    }

    public function execute(string|EasyQuery $query, array $args = []): bool
    {
        $sql = $query;
        if ($query instanceof EasyQuery) {
            $sql = $query->getQuery();
            $args = $query->getArgs();
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($args);
    }

    public function createStatement($query): bool|PDOStatement
    {
        $stmt = $this->db->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, DatabaseObject::class); // set to default fetch-mode :D
        return $stmt;
    }

    public function setClass($stmt, $class)
    {
        $stmt->setFetchMode(PDO::FETCH_CLASS, $class);
    }

    public function start()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }
}
