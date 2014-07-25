<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Component;

use CanalTP\SamEcoreApplicationManagerBundle\Component\BusinessComponentInterface;

abstract class AbstractBusinessComponent implements BusinessComponentInterface
{
    public function getMenuItems()
    {
        return null;
    }

    public function getPerimetersManager()
    {
        return null;
    }

    public function getPermissionsManager()
    {
        return null;
    }

    public function hasPerimeters()
    {
        return false;
    }
}
