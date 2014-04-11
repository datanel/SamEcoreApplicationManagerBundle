<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of CanalTPBusinessComponentCompilerPass
 *
 * @author akambi fagbohoun <contact@akambi-fagbohoun.com>
 */
class CanalTPBusinessComponentCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        preg_match_all(
            "|(?P<namespace>[^,]*)\\\CanalTP(?P<application>[^\\\]*)BridgeBundle|U",
            implode(',', $container->getParameter('kernel.bundles')),
            $applications,
            PREG_SET_ORDER
        );

        $factoryDefinition = $container
            ->register('sam.business_component', 'CanalTP\SamEcoreApplicationManagerBundle\Security\BusinessComponentRegistry')
            ->addArgument(new Reference('doctrine.orm.entity_manager'))
            ->addArgument(new Reference('session'))
            ->addArgument('%session_app_key%');

        foreach ($applications as $application) {
            $applicationName = strtolower($application['application']);

            // @todo Remove
            if (!$container->has('sam.business_component.' . $applicationName)) {
                continue;
            }

            $factoryDefinition->addMethodCall(
                'addBusinessComponent',
                array(
                    $applicationName,
                    new Reference('sam.business_component.' . $applicationName)
                )
            );
        }
    }
}
