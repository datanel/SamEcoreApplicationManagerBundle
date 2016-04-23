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
        $applicationAddedInRegistry = [];
        $factoryDefinition = $container->findDefinition('sam.business_component');

        $taggedServices = $container->findTaggedServiceIds(
            'sam.app_business_component'
        );
        foreach ($taggedServices as $id => $tags) {
            $factoryDefinition->addMethodCall(
                'addBusinessComponent',
                array(
                    $tags[0]['canonical_app_name'],
                    new Reference($id)
                )
            );

            $applicationAddedInRegistry[] = $tags[0]['canonical_app_name'];
        }
        
        // @todo: call this function from ApplicationFinder
        $bundles = $container->getParameter('kernel.bundles');
        $bridges = preg_grep(
            "|BridgeBundle|U",
            $container->getParameter('kernel.bundles')
        );
        $applications = preg_replace("/^.+\\\\(\w+)BridgeBundle\\\\.+$/", "$1", $bridges);

        foreach ($applications as $application) {
            $applicationName = strtolower($application);
            // @todo Remove
            if (!$container->has('sam.business_component.' . $applicationName)) {
                continue;
            }
             // Ensure that sam.business_component.${canonical_app_name} is not already added to the business component registry
            if (!in_array($applicationName, $applicationAddedInRegistry)) {
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
}
