<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Menu;

abstract class AbstractBusinessMenuItem implements BusinessMenuItemInterface 
{
    public function getAction()
    {
        return null;
    }

    public function getChildren()
    {
        return array();
    }

    public function getId()
    {
        return null;
    }

    public function getName()
    {
        return null;
    }

    public function getParameters()
    {
        return array();
    }

    public function getRoute()
    {
        return null;
    }
}
