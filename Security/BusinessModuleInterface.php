<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Security;

interface BusinessModuleInterface extends CommonBusinessInterface
{
    public function getPermissions();
    public function getNumberPermissions();
}
