<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

interface BusinessPermissionManagerInterface
{
    public function getPermissionManagementMode();
    public function getBusinessObjectTypes();
    public function getBusinessModules();
}
