<?php

/**
 * Description of ApplicationRegistration
 *
 * @author akambi fagbohoun <contact@akambi-fagbohoun.com>
 */

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Output\OutputInterface;
use CanalTP\SamCoreBundle\Entity\Application;

class ApplicationRegistration
{
    private $objectManager;
    private $aBundles;

    public function __construct(ObjectManager $em, $aBundles)
    {
        $this->objectManager = $em;
        $this->aBundles      = $aBundles;
    }

    public function register(OutputInterface $output)
    {
        $aExistApplications = $this->objectManager
            ->getRepository('CanalTPSamCoreBundle:Application')->findAll();
        $allApplication = array();
        foreach ($aExistApplications as $existApplication) {
            $allApplication[] = $existApplication->getName();
        }

        preg_match_all(
            "|\\\CanalTP(?P<applications>[^\\\]*)BusinessAppBundle|U",
            implode(',', $this->aBundles),
            $aApplications,
            PREG_PATTERN_ORDER
        );

        foreach ($aApplications['applications'] as $application) {
            if (!in_array($application, $allApplication)) {
                $app = new Application($application);
                $this->objectManager->persist($app);
                $output->writeln('Insert Application ' . $application);
            }
        }

        $this->objectManager ->flush();

    }
}
