<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

use CanalTP\SamEcoreApplicationManagerBundle\Security\BusinessPermission;

abstract class AbstractBusinessModule implements BusinessModuleInterface
{
    protected $permissions;
    protected $permissionsObject;

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
        if (null !== $this->permissionsObject) {
            return $this->permissionsObject;
        }

        $permissions = array();
        foreach ($this->permissions as $key => $permission) {
            $model = new BusinessPermission();
            $model->setName($permission['label']);
            $model->setDescription($permission['description']);
            $model->setId($key);
            $permissions[] = $model;
        }

        $this->permissionsObject = $permissions;

        return $this->permissionsObject;
    }
}
