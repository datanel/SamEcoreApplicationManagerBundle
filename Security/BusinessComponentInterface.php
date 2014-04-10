<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

interface BusinessComponentInterface extends CommonBusinessInterface
{
    public function hasPerimeters();
    public function getMenuItems();
    public function getPerimetersManager();
    public function getPermissionsManager();
}
