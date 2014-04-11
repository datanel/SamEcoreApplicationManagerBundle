<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

interface BusinessComponentInterface extends CommonBusinessInterface
{
    /**
     * Check if there are perimeters
     *
     * @return boolean
     */
    public function hasPerimeters();

    /**
     * [getMenuItems description]
     * @return [type] [description]
     */
    public function getMenuItems();

    /**
     * Get the perimeter manager
     *
     * @return BusinessPerimeterManagerInterface The perimeter manager
     */
    public function getPerimetersManager();

    /**
     * Get the permission manager
     *
     * @return BusinessPermissionManagerInterface The permission manager
     */
    public function getPermissionsManager();
}
