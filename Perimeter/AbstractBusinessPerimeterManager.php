<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Perimeter;

abstract class AbstractBusinessPerimeterManager implements BusinessPerimeterManagerInterface
{
    public function addUserToPerimeter(\FOS\UserBundle\Model\UserInterface $user, BusinessPerimeterInterface $perimeter)
    {
        return false;
    }

    public function deleteUserFromPerimeter(\FOS\UserBundle\Model\UserInterface $user, BusinessPerimeterInterface $perimeter)
    {
        return false;
    }

    public function deleteUserPerimeters(\FOS\UserBundle\Model\UserInterface $user)
    {
        return false;
    }

    public function getPerimeters()
    {
        return array();
    }

    public function getUserPerimeters(\FOS\UserBundle\Model\UserInterface $user)
    {
        return array();
    }
}
