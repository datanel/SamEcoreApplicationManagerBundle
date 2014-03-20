<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Security;

interface BusinessPermissionManagerInterface
{
    public function getPermissionManagementMode();
    public function getBusinessObjectTypes();
    public function getBusinessModules();
}
