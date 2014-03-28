<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\DependencyInjection\Compiler;

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
        $aApplicationMetiers = array();
        preg_match_all("|([^,]*)\\\CanalTP([^\\\]*)BusinessAppBundle|U",
                       implode(',', $container->getParameter('kernel.bundles')),
                       $aApplicationMetiers,
                       PREG_SET_ORDER);

        foreach ($aApplicationMetiers as $aApplicationMetier) {

            $namespace            = $aApplicationMetier[1];
            $application          = strtolower($aApplicationMetier[2]);
            $businessModuleId     = 'sam.business_module.' . $application;
            $businessPermissionId = 'sam.business_permission_manager.' . $application;
            $businessComponentId  = 'sam.business_component.' . $application;

            // define business module service of this application
            $container
                    ->register($businessModuleId, $namespace . '\Security\BusinessModule')
                    ->addArgument('%permissions%')
                    ->setPublic(false);

            // define business permission manager service of this application
            $container
                    ->register($businessPermissionId, $namespace . '\Security\BusinessPermissionManager')
                    ->addArgument(new Reference($businessModuleId))
                    ->setPublic(false);

            // define business component service of this application
            $container
                    ->register($businessComponentId, $namespace . '\Security\BusinessComponent')
                    ->addArgument(new Reference($businessPermissionId))
                    ->addArgument(new Reference('service_container'))
                    ->setPublic(false)
                    ->addTag('sam.business_component', array('application' => $application));
        }

        $factoryDefinition = $container
                ->register('sam.business_component', 'CanalTP\Sam\Ecore\ApplicationManagerBundle\Security\BusinessComponentFactory')
                ->addArgument(new Reference('doctrine.orm.entity_manager'))
                ->addArgument(new Reference('session'))
                ->addArgument('%session_app_key%');

        $taggedServices = $container->findTaggedServiceIds(
            'sam.business_component'
        );
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $factoryDefinition->addMethodCall(
                    'addBusinessComponent',
                    array(
                    new Reference($id), $attributes["application"])
                );
            }
        }
    }
}
