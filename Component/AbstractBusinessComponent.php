<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Component;

use CanalTP\SamEcoreApplicationManagerBundle\Component\BusinessComponentInterface;

abstract class AbstractBusinessComponent implements BusinessComponentInterface
{
    public function getMenuItems()
    {
        return array();
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
    
    public function getId() {
        return '';
    }

    public function getName()
    {
        return '';
    }
    
}
