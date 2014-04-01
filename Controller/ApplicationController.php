<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApplicationController extends Controller
{
    public function changeAction($application)
    {
        $em = $this->getDoctrine()->getManager();
        $oApplication = $em->getRepository('CanalTPSamCoreBundle:Application')->find($application);
        $appKey = $this->container->getParameter('session_app_key');
        $this->get('session')->set($appKey, $application);
//        $this->get('session')->set('selectedappname', strtoupper($oApplication->getName()));

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
}
