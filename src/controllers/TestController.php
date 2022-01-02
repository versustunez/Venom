<?php


namespace Controllers;


use Venom\Views\Asset;
use Venom\Views\RenderController;
use Venom\Views\VenomRenderer;

class TestController implements RenderController
{

    public function register(): bool
    {
        return true;
    }

    public function render(VenomRenderer $renderer): bool
    {
        Asset::get()->addJS('test', 'test.js');
        Asset::get()->addCSS('test', 'test.css');
        return true;
    }

    public function getTemplate(): string
    {
        return 'base';
    }
}