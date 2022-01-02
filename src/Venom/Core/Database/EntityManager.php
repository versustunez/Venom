<?php

namespace Venom\Core\Database;

use Exception;

class EntityManager
{
    /** @var Entity[] */
    private array $entities = [];

    public function __construct(private string $classType, private Database $db)
    {
    }

    public static function create($callable): EntityManager
    {
        return DatabaseHandler::getEntityManager($callable);
    }

    public static function new($callable): EntityManager
    {
        return DatabaseHandler::createEntityManager($callable);
    }

    public function createEntity()
    {
        $ent = new $this->classType;
        $this->entities[] = $ent;
        return $ent;
    }

    public function addEntity(Entity $entity)
    {
        $this->entities[] = $entity;
    }

    public function removeEntity(Entity $entity)
    {
        foreach ($this->entities as $key => $item) {
            if ($entity === $item) {
                unset($this->entities[$key]);
                break;
            }
        }
    }

    public function findBy($key, $value): ?Entity
    {
        foreach ($this->entities as $entity) {
            if ($entity->$key === $value) {
                return $entity;
            }
        }
        return null;
    }

    public function saveAll()
    {
        if (count($this->entities) === 0) {
            return;
        }
        try {
            $this->db->start();
            foreach ($this->entities as $entity) {
                $entity->save();
            }
            $this->db->commit();
        } catch (Exception $ex) {
            trigger_error($ex->getMessage());
            $this->db->rollBack();
        }
    }

    public function deleteEntities()
    {
        try {
            $this->db->start();
            foreach ($this->entities as $entity) {
                $entity->delete();
            }
            $this->db->commit();
        } catch (Exception $ex) {
            trigger_error($ex->getMessage());
            $this->db->rollBack();
        }
    }

    public function clearAll()
    {
        $this->entities = [];
    }

    public function loadBy(string|EasyQuery $query, $args = [], array $fields = ["*"]): array
    {
        $sql = $query;
        if ($query instanceof EasyQuery) {
            $query->buildSelect();
            $sql = $query->getQuery();
            $args = $query->getArgs();
            $fields = $query->getFields();
        }
        $stmt = $this->db->createStatement($sql);
        $this->db->setClass($stmt, $this->classType);
        if ($stmt->execute($args)) {
            /** @var Entity[] $all */
            $all = $stmt->fetchAll();
            foreach ($all as $item) {
                $item->loadedFields = $fields;
                $item->postLoad();
                $this->addEntity($item);
            }
            return $all;
        }
        return [];
    }

    public function execute($query): bool
    {
        return $this->db->execute($query);
    }

    public function getAll(): array
    {
        return $this->entities;
    }

    private function addEntities(array $entities)
    {
        foreach ($entities as $entity) {
            $this->entities[] = $entity;
        }
    }
}
