<?php


namespace Venom\Helper;


use Venom\Core\ArgumentHandler;
use Venom\Core\Database\DatabaseHandler;

/**
 * Class MetaGenerator
 * @package Venom\Helper
 */
class MetaGenerator
{
    private array $container = [];
    private string $id;

    public function __construct()
    {
        $this->id = (string)ArgumentHandler::get()->getItem('metaId', '-1');
    }

    public function loadById(): void
    {
        if ($this->id === '-1') {
            return;
        }
        $db = DatabaseHandler::get();
        $data = $db->getOne('select content from metaTagData where id = :id', [':id' => $this->id]);
        if ($data !== null) {
            $this->container = json_decode($data->content ?? '', true);
            $this->container = array_merge([], $this->container);
        }
    }

    public function render(): void
    {
        foreach ($this->container as $key => $value) {
            echo '<meta name="' . $key . '" content="' . $value . '">';
        }
    }
}
