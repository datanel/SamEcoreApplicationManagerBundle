<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Menu;

use CanalTP\SamEcoreApplicationManagerBundle\CommonBusinessInterface;

interface BusinessMenuItemInterface extends CommonBusinessInterface
{
    public function getChildren();
    public function getAction();
    public function getRoute();
    public function getParameters();
}