<?php


namespace Modules\Role\Controller;


use Venom\Helper\AdminHelper;

class RoleController
{
    public function get()
    {
        AdminHelper::sendResponse([
            'roles' => [
                ['id' => 1, 'name' => 'Admin', 'icon' => 'vt-visibility'],
                ['id' => 2, 'name' => 'Moderator', 'icon' => 'vt-edit'],
                ['id' => 3, 'name' => 'Gast', 'icon' => 'vt-edit'],
            ]
        ]);
    }
}