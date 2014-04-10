<?php

namespace CanalTP\SamEcoreApplicationManagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use CanalTP\SamEcoreApplicationManagerBundle\DependencyInjection\Compiler\CanalTPBusinessComponentCompilerPass;

class CanalTPSamEcoreApplicationManagerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CanalTPBusinessComponentCompilerPass);
    }
}
