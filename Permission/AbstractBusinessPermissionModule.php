<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Permission;

use CanalTP\SamEcoreApplicationManagerBundle\Permission\BusinessPermission;

abstract class AbstractBusinessPermissionModule implements BusinessPermissionModuleInterface
{
    protected $permissions;
    protected $permissionsObject;

    public function __construct(array $permissions)
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
    
    public function getId()
    {
        return null;
    }

    public function getName()
    {
        return '';
    }
}
