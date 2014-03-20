<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Security;

interface BusinessComponentInterface extends CommonBusinessInterface
{
    public function hasPerimeters();
    public function getMenuItems();
    public function getPerimetersManager();
    public function getPermissionsManager();
}
