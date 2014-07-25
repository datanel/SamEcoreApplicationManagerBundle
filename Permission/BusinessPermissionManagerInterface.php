<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Permission;

interface BusinessPermissionManagerInterface
{
    public function getPermissionManagementMode();
    public function getBusinessObjectTypes();
    public function getBusinessModules();
}
