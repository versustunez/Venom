<?php


namespace Venom\Views;


interface RenderController
{
    public function register(): bool;

    public function render(VenomRenderer $renderer): bool;

    public function getTemplate(): string;
}