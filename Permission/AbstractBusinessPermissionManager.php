<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Permission;

use CanalTP\SamEcoreApplicationManagerBundle\Permission\BusinessPermissionManagerInterface;

abstract class AbstractBusinessPermissionManager implements BusinessPermissionManagerInterface
{
    public function getBusinessModules()
    {
        return null;
    }

    public function getBusinessObjectTypes()
    {
        return null;
    }

    public function getPermissionManagementMode()
    {
        return null;
    }
}
