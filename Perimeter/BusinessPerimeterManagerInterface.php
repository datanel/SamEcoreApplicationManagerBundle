<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Perimeter;

use FOS\UserBundle\Model\UserInterface;
use CanalTP\SamEcoreApplicationManagerBundle\CommonBusinessInterface;

interface BusinessPerimeterManagerInterface extends CommonBusinessInterface
{
    /**
     * Add a user to a perimeter
     *
     * @param UserInterface              $user
     * @param BusinessPerimeterInterface $perimeter
     */
    public function addUserToPerimeter(UserInterface $user, BusinessPerimeterInterface $perimeter);

    /**
     * Get the perimeters
     *
     * @return BusinessPerimeterInterface[] The perimeters
     */
    public function getPerimeters();

    /**
     * Delete a user from a perimeter
     *
     * @param UserInterface              $user
     * @param BusinessPerimeterInterface $perimeter
     */
    public function deleteUserFromPerimeter(UserInterface $user, BusinessPerimeterInterface $perimeter);

    /**
     * Delete all the perimeters of a user
     *
     * @param UserInterface $user
     */
    public function deleteUserPerimeters(UserInterface $user);

    /**
     * Get a user's perimeters
     *
     * @param UserInterface $user
     *
     * @return BusinessPerimeterInterface[] The perimeters
     */
    public function getUserPerimeters(UserInterface $user);
}
