<?php

/**
 * Description of ApplicationManagerCommand
 *
 * @author akambi
 */

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApplicationManagerCommand extends ContainerAwareCommand
{

    private $container;

    protected function configure()
    {
        $this
            ->setName('sam:update_application')
            ->setDescription('Update applications in database')
        ;
    }

    protected function execute(InputInterface $input,
                               OutputInterface $output)
    {
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $em = $this->container->get('canal_tp_sam_ecore_application_registration')->register($output);
    }
}
