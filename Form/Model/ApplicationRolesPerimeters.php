<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Form\Model;

class ApplicationRolesPerimeters
{
    public $superAdmin;
    public $application;
    
    public function __get($name)
    {
        $getMethod = 'get' . ucfirst($name);
        return $this->application->$getMethod();
    }
    
    public function __set($name, $arguments)
    {
        $setMethod = 'set' . ucfirst($name);
        return call_user_func(array($this->application, $setMethod), $arguments);
    }
    
    public function __call($name, $arguments) {
        if (preg_match('/^(set|get|add)/', $name)) {
            return call_user_func(array($this->application, $name), $arguments);
        }
    }
}
