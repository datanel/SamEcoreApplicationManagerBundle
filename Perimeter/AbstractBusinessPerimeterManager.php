<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Perimeter;

use FOS\UserBundle\Model\UserInterface;

abstract class AbstractBusinessPerimeterManager implements BusinessPerimeterManagerInterface
{
    public function addUserToPerimeter(UserInterface $user, BusinessPerimeterInterface $perimeter)
    {
        return false;
    }

    public function deleteUserFromPerimeter(UserInterface $user, BusinessPerimeterInterface $perimeter)
    {
        return false;
    }

    public function deleteUserPerimeters(UserInterface $user)
    {
        return false;
    }

    public function getPerimeters()
    {
        return array();
    }

    public function getUserPerimeters(UserInterface $user)
    {
        return array();
    }
    
    public function getId()
    {
        return null;
    }

    public function getName()
    {
        return '';
    }
}
