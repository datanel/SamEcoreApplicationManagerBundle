<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

use CanalTP\SamEcoreApplicationManagerBundle\Security\BusinessPermission;

abstract class AbstractBusinessModule implements BusinessModuleInterface
{
    protected $permissions = null;

    public function __construct($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getNumberPermissions()
    {
        return count($this->permissions);
    }

    public function getPermissions()
    {
        $permissions = array();
        foreach ($this->permissions as $key => $permission) {
            $model = new BusinessPermission();
            $model->setName($permission['label']);
            $model->setDescription($permission['description']);
            $model->setId($key);
            $permissions[] = $model;
        }

        return $permissions;
    }
}
