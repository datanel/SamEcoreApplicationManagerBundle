<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Menu;

abstract class AbstractBusinessMenuItem implements BusinessMenuItemInterface 
{
    protected $isActive;
    protected $attributes = array();

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
    
    public function setActive($isActive = true)
    {
        $this->isActive = $isActive;
    }
    
    public function isActive()
    {
        return $this->isActive;
    }
    
    public function addAttribute(array $attr) 
    {
        $this->attributes += $attr;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }
}
