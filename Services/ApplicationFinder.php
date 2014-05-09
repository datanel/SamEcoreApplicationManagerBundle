<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Services;

use \Symfony\Component\HttpFoundation\RequestStack;

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

    protected $currentApp = null;

    public function __construct(RequestStack $rs, $em, $appEntityName) {

        $this->requestStack = $rs;
        $this->em = $em;
        $this->applicationEntityName = $appEntityName;
    }

    public function findFromUrl()
    {
        if (is_null($this->currentApp)) {
            $res = array();
            preg_match('/\/(\w+)/', $this->requestStack->getCurrentRequest()->getPathInfo(), $res);

            //admin is a sam synonyme
            if (strtolower($res[1]) == 'admin') {
                $res[1] = 'sam';
            }

            $app = $this->em->getRepository($this->applicationEntityName)->findOneBy(array('canonicalName' => $res[1]));

            $this->currentApp = $app;
        }

        return $this->currentApp;
    }
}
