<?php


namespace Modules\Meta\Controller;


use Venom\Core\DatabaseHandler;
use Venom\Helper\AdminHelper;

class MetaAPIController
{
    public function get()
    {
        AdminHelper::sendResponse([]);
    }

    public function getById($id)
    {
        AdminHelper::sendResponse(SeoUrlController::getById($id));
    }

    public function update($id)
    {
        return true;
    }

    public function delete($id)
    {
        return true;
    }

    public function create($id)
    {
        return true;
    }
}