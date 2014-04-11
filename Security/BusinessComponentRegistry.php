<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Security;

use CanalTP\SamEcoreApplicationManagerBundle\Security\BusinessComponentInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use CanalTP\SamEcoreApplicationManagerBundle\Exception\OutOfBoundsException;

class BusinessComponentRegistry
{
    private $em;
    private $session;
    private $appKey;
    private $businessComponents = array();

    public function __construct(EntityManager $em, Session $session, $appKey)
    {
        $this->em = $em;
        $this->session = $session;
        $this->appKey = $appKey;
    }

    public function addBusinessComponent($application, BusinessComponentInterface $businessComponent)
    {
        $this->businessComponents[$application] = $businessComponent;
    }

    public function getBusinessComponent($application = null)
    {
        if ($application == null) {
            if ($this->session->has($this->appKey)) {
                $app_id = $this->session->get($this->appKey);
                $oApplication = $this->em->getRepository('CanalTPSamCoreBundle:Application')
                    ->find($app_id);
                $application = strtolower($oApplication->getName());
            } else {
                return;
            }
        }

        if (array_key_exists($application, $this->businessComponents)) {
           return $this->businessComponents[$application];
        }

        throw new OutOfBoundsException(sprintf('business component for %s application not found', $application));
    }
}
