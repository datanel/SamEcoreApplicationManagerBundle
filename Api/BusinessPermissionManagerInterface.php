<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Api;

interface BusinessPermissionManagerInterface
{
    public function getPermissionManagementMode();
    public function getBusinessObjectTypes();
    public function getBusinessModules();
}
