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
            ->register('sam.business_component', 'CanalTP\SamEcoreApplicationManagerBundle\Security\BusinessComponentFactory')
            ->addArgument(new Reference('doctrine.orm.entity_manager'))
            ->addArgument(new Reference('session'))
            ->addArgument('%session_app_key%');

        foreach ($applications as $application) {
            $namespace            = $application['namespace'];
            $application          = strtolower($application['application']);
            $businessModuleId     = 'sam.business_module.' . $application;
            $businessPermissionId = 'sam.business_permission_manager.' . $application;
            $businessPerimeterId  = 'sam.business_perimeter_manager.' . $application;
            $businessComponentId  = 'sam.business_component.' . $application;

            // define business component service of this application
            $container
                ->register($businessComponentId, $namespace . '\Security\BusinessComponent')
                ->addArgument(new Reference($businessPermissionId))
                ->addArgument(new Reference($businessPerimeterId))
                ->setPublic(false);

            $factoryDefinition->addMethodCall(
                'addBusinessComponent',
                array($application, new Reference($businessComponentId))
            );
        }

        $factoryDefinition = $container
                ->register('sam.business_component', 'CanalTP\SamEcoreApplicationManagerBundle\Security\BusinessComponentFactory')
                ->addArgument(new Reference('doctrine.orm.entity_manager'))
                ->addArgument(new Reference('session'))
                ->addArgument('%session_app_key%');

            $factoryDefinition->addMethodCall(
                'addBusinessComponent',
                array(new Reference($businessComponentId), $application)
            );
    }
}
