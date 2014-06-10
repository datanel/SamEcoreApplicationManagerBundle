<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

/**
 * Allow to get application by several ways
 *
 *
 * @author Kévin ZIEMIANSKI
 */
class ApplicationFinder
{
    protected $requestStack;
    protected $em;
    protected $applicationEntityName;
    protected $container;

    protected $currentApp = null;

    public function __construct($container, $appEntityName)
    {
        $this->requestStack = $container->get('request_stack');
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->applicationEntityName = $appEntityName;
        $this->container = $container;
    }

    public function findFromUrl()
    {
        if (is_null($this->currentApp)) {
            $res = array();
            preg_match('/\/(\w+)/', $this->requestStack->getMasterRequest()->getPathInfo(), $res);
            $appName = '';

            if (empty($res)) {
                //Get first user's app
                $userRoles = $this->container->get('security.context')->getToken()->getUser()->getUserRoles();

                if (empty($userRoles)) {
                    throw new AccessDeniedException('Votre profil n\'a pas de rôle. Contactez un administrateur.');
                }

                $appName = $userRoles->first()->getApplication()->getCanonicalName();
            } else {
                $appName = strtolower($res[1]);
            }

            //admin is a sam synonyme
            if ($appName == 'admin') {
                $appName = 'sam';
            }

            $app = $this->em->getRepository($this->applicationEntityName)->findOneBy(array('canonicalName' => $appName));

            debug($appName);
            
            $this->currentApp = $app;
        }

        return $this->currentApp;
    }

    public function getCurrentApp()
    {
        if ($this->requestStack->getCurrentRequest()->query->has('app')) {
            $app = $this->em->getRepository($this->applicationEntityName)->findOneBy(
                array(
                    'canonicalName' => $this->requestStack->getCurrentRequest()->query->get('app')
                )
            );
            $this->currentApp = $app;
        } else {
            $this->findFromUrl();
        }
            return ($this->currentApp);
    }
}
