<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

interface BusinessMenuItemInterface extends CommonBusinessInterface
{
    public function getChildren();
    public function getAction();
}