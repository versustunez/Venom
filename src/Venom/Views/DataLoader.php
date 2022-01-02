<?php


namespace Venom\Views;

use RuntimeException;
use Venom\Core\DatabaseHandler;
use Venom\Entities\DataEntity;

class DataLoader
{
    private static ?DataLoader $instance = null;

    private function __construct()
    {
    }

    public static function get(): DataLoader
    {
        if (self::$instance === null) {
            self::$instance = new DataLoader();
        }
        return self::$instance;
    }

    public static function loadById(string $id): ?DataEntity
    {
        if ($id === '') {
            throw new RuntimeException('Try to Load empty ID from Database');
        }
        $data = DatabaseHandler::get()->getOne('SELECT identity, raw, generated, datatype FROM data WHERE identity = :id and isActive = 1 LIMIT 1', [
            ':id' => $id
        ]);

        if ($data !== null) {
            $model = new DataEntity($data->identity, $data->datatype, $data->raw, $data->generated);
            $model->setActive(true);
            return $model;
        }
        return null;
    }

    public function updateData(DataEntity $model): bool
    {
        if ($model->getId() === '') {
            return $this->insertData($model);
        }
        return DatabaseHandler::get()->execute(
            "UPDATE data SET identity=:id, isActive=:isActive, generated=:gen, raw=:raw, datatype=:dt WHERE identity=:id",
            [
                ':id' => $model->getId(),
                ':isActive' => $model->getActive(),
                ':gen' => $model->getGenerated(),
                ':raw' => $model->getRaw(),
                ':dt' => $model->getType()
            ]
        );
    }

    public function insertData(DataEntity $model): bool
    {

        $this->validateModel($model);
        return DatabaseHandler::get()->execute(
            "INSERT INTO data (identity, isActive, generated, raw, datatype) VALUES (:id, :isActive, :gen, :raw, :dt)",
            [
                ':id' => $model->getId(),
                ':isActive' => $model->getActive(),
                ':gen' => $model->getGenerated(),
                ':raw' => $model->getRaw(),
                ':dt' => $model->getType()
            ]
        );
    }

    private function validateModel(DataEntity $model): void
    {
        if ($model->getId() === '') {
            $model->setId($this->generateID());
        }
        if (!$model->validate()) {
            $id = htmlspecialchars($model->getId());
            throw new RuntimeException("DataModel with id: \"$id\" is invalid!");
        }
    }

    private function generateID(string $id = ''): string
    {
        if ($id === '') {
            $id = bin2hex(random_bytes(32));
        }
        return hash('SHA256', $id);
    }
}