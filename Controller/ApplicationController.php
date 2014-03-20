<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApplicationController extends Controller
{
    public function changeAction($application)
    {
        $appKey = $this->container->getParameter('session_app_key');
        $this->get('session')->set($appKey, $application);
        $em = $this->getDoctrine()->getManager();
        $oApplication = $em->getRepository('CanalTPIussaadCoreBundle:Application')
            ->find($application);
        $this->get('session')->set('selectedappname', strtoupper($oApplication->getName()));
        return $this->redirect($this->generateUrl('root'));
    }
    
    public function toolbarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $aApplications = $em->getRepository('CanalTPIussaadCoreBundle:Application')
            ->findAll();
            return $this->render(
                'CanalTPSamEcoreApplicationManagerBundle:Application:toolbar.html.twig',
                array(
                    'applications' => $aApplications
                    )
            );
    }    
}
