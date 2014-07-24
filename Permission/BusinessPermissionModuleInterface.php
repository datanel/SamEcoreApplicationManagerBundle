<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

interface BusinessPermissionModuleInterface extends CommonBusinessInterface
{
    /**
     * Get the permissions
     *
     * @return BusinessPermissionInterface[] The permissions
     */
    public function getPermissions();

    /**
     * Get the number of permissions
     *
     * @return integer The number of permissions
     */
    public function getNumberPermissions();
}
