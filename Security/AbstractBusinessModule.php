<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

abstract class AbstractBusinessModule implements BusinessModuleInterface
{
    protected $permissions = null;

    public function __construct($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getNumberPermissions()
    {
        return count($this->permissions);
    }
}
