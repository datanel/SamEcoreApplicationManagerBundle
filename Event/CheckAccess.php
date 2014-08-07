<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Event;

use FOS\UserBundle\Model\UserInterface;

/**
 * Throw an exception if user is not allowed
 *
 * @author Kévin Ziemianski <kevin.ziemianski@canaltp.fr>
 */
class CheckAccess
{
    protected $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $token = $this->container->get('security.context')->getToken();
        if (is_null($token) || $event->getRequestType() != \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
            return;
        }
        $user = $token->getUser();
        $route = $event->getRequest()->attributes->get('_route');
        $appService = $this->container->get('canal_tp_sam.application.finder');
        
        if (!$user instanceof UserInterface) {
            return;
        }
        $userApps = $appService->getUserApps($user);
        
        $matches = array();
        preg_match('/\/([a-z0-9]*)/', $event->getRequest()->getPathInfo(), $matches);
        if (!isset($matches[1]) || $matches[1] == '') {
            return;
        }
        
        if (!in_array($matches[1], $userApps)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        
        return true;
    }
}
