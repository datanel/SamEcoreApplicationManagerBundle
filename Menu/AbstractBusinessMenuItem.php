<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Menu;

abstract class AbstractBusinessMenuItem implements BusinessMenuItemInterface
{
    protected $isActive;
    protected $attributes = array();
    protected $routePatternForHighlight = array();

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

    public function isActive($route = '')
    {
        if (isset($this->isActive)) {
            return $this->isActive;
        }

        foreach ($this->routePatternForHighlight as $routePattern) {
            if ($routePattern != '' && preg_match($routePattern, $route)) {
                return true;
            }
        }

        return false;
    }

    public function addAttribute(array $attr)
    {
        $this->attributes += $attr;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setRoutePatternForHighlight($pattern)
    {
        $this->routePatternForHighlight = $pattern;
    }
}
