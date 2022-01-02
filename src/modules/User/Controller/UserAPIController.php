<?php


namespace Modules\User\Controller;

use Venom\Core\ArgumentHandler;
use Venom\Core\Database\DatabaseHandler;
use Venom\Core\Database\EasyQuery;
use Venom\Core\Database\EntityManager;
use Venom\Entities\User;
use Venom\Helper\AdminHelper;

class UserAPIController
{
    public function get()
    {
        $entityManager = EntityManager::create(User::class);
        $easyQuery = new EasyQuery(User::$tableName, ["id", "username", "firstname", "lastname", "email", "isActive"]);
        $entityManager->loadBy($easyQuery);
        AdminHelper::sendResponse(["users" => $entityManager->getAll()]);
    }

    public function getById($id)
    {
        $d = UserController::getById($id, ["id", "username", "firstname", "lastname", "email", "isActive"]);
        AdminHelper::sendResponse($d);
    }

    public function update($id)
    {
        $original = UserController::getById($id);
        if ($original == null) {
            AdminHelper::sendStatus(false, "User not Found");
        }
        $args = ArgumentHandler::get();
        $data = [];
        $d = $original->getFields();
        foreach ($d as $key) {
            if ($args->hasPostItem($key)) {
                $val = $args->getPostItem($key);
                if ($val != $original->$key) {
                    $data[$key] = $val;
                }
            }
        }
        AdminHelper::sendStatus(UserController::update($id, $data));
    }

    public function delete($id)
    {
    }

    public function create($id)
    {
        // INSERT INTO
        AdminHelper::sendStatus(true);
    }
}
