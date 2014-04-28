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
        $appKey = $this->container->getParameter('session_app_key');
        $this->get('session')->set($appKey, $oApplication->getCanonicalName());

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
        $aApplications = $em->getRepository('CanalTPSamCoreBundle:Application')->findAll();

        return $this->render(
            'CanalTPSamEcoreApplicationManagerBundle:Application:toolbar.html.twig',
            array('applications' => $aApplications)
        );
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
        $path = $kernel->locateResource('@CanalTP' . $application->getName() . 'Bundle/CHANGELOG.md');

        return new BinaryFileResponse($path);
    }
}
