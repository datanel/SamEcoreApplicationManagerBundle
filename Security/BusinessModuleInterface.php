<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

interface BusinessModuleInterface extends CommonBusinessInterface
{
    public function getPermissions();
    public function getNumberPermissions();
}
