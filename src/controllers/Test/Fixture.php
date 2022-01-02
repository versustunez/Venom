<?php


namespace Controllers\Test;


use Venom\Entities\DataEntity;
use Venom\Views\DataLoader;
use Venom\Views\RenderController;
use Venom\Views\VenomRenderer;

class Fixture implements RenderController
{

    public function register(): bool
    {
        return true;
    }

    public function render(VenomRenderer $renderer): bool
    {
        $data = new DataEntity('', DataEntity::TYPE_CONTENT, 'blubiblub', '<h1>Hallo funktioniert</h1>');
        echo 'penis';
        return DataLoader::get()->insertData($data);
    }

    public function getTemplate(): string
    {
        return 'async';
    }
}
