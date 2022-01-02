<?php


namespace Modules\User\Controller;


use Venom\Core\Database\EasyQuery;
use Venom\Core\Database\EntityManager;
use Venom\Entities\DatabaseObject;
use Venom\Entities\User;

class UserController
{

    public static function getById($id, array $fields = ["*"]): ?User
    {
        if (empty($id)) {
            return null;
        }
        $entityManager = EntityManager::create(User::class);
        $query = new EasyQuery(User::$tableName, $fields);
        $query->where("id", $id);
        $data = $entityManager->loadBy($query);
        return !empty($data) ? $data[0] : null;
    }

    public static function update($id, array $values = []): bool
    {
        if (count($values) === 0) {
            return false;
        }
        $entityManager = EntityManager::create(User::class);
        $entity = $entityManager->findBy('id', $id);
        if ($entity) {
            foreach ($values as $key => $value) {
                $entity->$key = $value;
            }
            return $entity->save();
        }
        return false;
    }
}
