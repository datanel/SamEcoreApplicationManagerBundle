<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class ApplicationController extends Controller
{
    public function changeAction($application)
    {
        $em = $this->getDoctrine()->getManager();
        $oApplication = $em->getRepository('CanalTPSamCoreBundle:Application')->find($application);

        $defaultUrl = $oApplication->getDefaultRoute();

        if (is_null($defaultUrl)) {
            throw new \Exception('There is no default route for this application');
        }

        $defaultRoute = $this->get('router')->match($defaultUrl);

        return $this->redirect($this->get('router')->generate($defaultRoute["_route"]));
    }

    public function toolbarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $applications = $em->getRepository('CanalTPSamCoreBundle:Application')->findByUser($this->getUser());
        
        if (count($applications) > 1) {
            return $this->render(
                'CanalTPSamEcoreApplicationManagerBundle:Application:toolbar.html.twig',
                array('applications' => $applications)
            );
        } else {
            //Only one apps, so don't display menu
            return new \Symfony\Component\HttpFoundation\Response();
        }
    }

    /**
     * Display CHANGELOG.md file for each application
     */
    public function changelogAction(Request $request, $canonicalName)
    {
        $kernel = $this->get('kernel');
        $em = $this->getDoctrine()->getManager();
        $application = $em
            ->getRepository('CanalTPSamCoreBundle:Application')
            ->findOneByCanonicalName($canonicalName);

        if (is_null($application)) {
            throw $this->createNotFoundException('Application (' . $canonicalName . ') not found.');
        }
        try {
            $path = $kernel->locateResource('@CanalTP' . $application->getName() . 'Bundle/CHANGELOG.md');
        } catch (\InvalidArgumentException $e) {
            throw $this->createNotFoundException('Changelog file for ' . $application->getName() . ' not found.');
        }
        return new BinaryFileResponse($path);
    }
}
