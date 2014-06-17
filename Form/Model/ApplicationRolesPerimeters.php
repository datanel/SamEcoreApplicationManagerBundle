<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Form\Model;

class ApplicationRolesPerimeters
{
    public $superAdmin;
    public $application;
    
    public function __construct()
    {
        $this->superAdmin = false;
    }
}
