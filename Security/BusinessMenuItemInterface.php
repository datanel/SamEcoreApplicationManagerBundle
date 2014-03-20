<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Security;

interface BusinessMenuItemInterface extends CommonBusinessInterface
{
    public function getChildren();
    public function getAction();
}