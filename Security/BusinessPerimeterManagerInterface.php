<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

use FOS\UserBundle\Model\UserInterface;

interface BusinessPerimeterManagerInterface
{
    public function addUserToPerimeter(UserInterface $user, BusinessPerimeterInterface $perimeter);
    public function getPerimeters();
    public function deleteUserFromPerimeter(UserInterface $user, BusinessPerimeterInterface $perimeter);
}
