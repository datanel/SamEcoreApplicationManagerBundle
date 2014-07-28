<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Permission;

use CanalTP\SamEcoreApplicationManagerBundle\CommonBusinessInterface;

interface BusinessPermissionManagerInterface extends CommonBusinessInterface
{
    public function getPermissionManagementMode();
    public function getBusinessObjectTypes();
    public function getBusinessModules();
}
