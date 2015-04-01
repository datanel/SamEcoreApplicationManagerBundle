<?php

/**
 * Description of ApplicationRegistration
 *
 * @author akambi fagbohoun <contact@akambi-fagbohoun.com>
 */

namespace CanalTP\SamEcoreApplicationManagerBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Output\OutputInterface;
use CanalTP\SamCoreBundle\Entity\Application;

class ApplicationRegistration
{
    private $objectManager;
    private $applicationFinder;

    public function __construct(ObjectManager $em, $applicationFinder)
    {
        $this->objectManager        = $em;
        $this->applicationFinder    = $applicationFinder;
    }

    public function register(OutputInterface $output)
    {
        $aExistApplications = $this->objectManager->getRepository('CanalTPSamCoreBundle:Application')->findAll();
        $allApplication = array();
        foreach ($aExistApplications as $existApplication) {
            $allApplication[] = $existApplication->getName();
        }

        $applications = $applicationFinder->getBridgeApplicationBundles();

        foreach ($applications as $application) {
            if (!in_array($application['app'], $allApplication)) {
                $app = new Application($application);
                $this->objectManager->persist($app);
                $output->writeln('Insert Application ' . $application);
            }
        }
        $this->objectManager ->flush();
    }
}
