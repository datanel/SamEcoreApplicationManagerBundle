<?php

/**
 * Description of RoutingLoader
 *
 * Cette classe charge les routing Yml pour un client donné
 *
 * @author akambi
 * @author Kévin Ziemianski <kevin.ziemianski@canaltp.fr>
 */
namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class ApplicationRoutingLoader extends Loader
{
    private $loaded = false;
    private $aBundles;
    private $routePrefix;

    public function __construct(
        $aBundles, $routePrefix
    )
    {
        $this->aBundles = $aBundles;
        $this->routePrefix = $routePrefix;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $this->loaded = true;

        $collection = new RouteCollection();
        $aApplications = array();

        preg_match_all("|\\\CanalTP([^\\\]*)BusinessAppBundle|U",
                       implode(',',
                               $this->aBundles),
                               $aApplications,
                               PREG_PATTERN_ORDER);

        foreach ($aApplications[1] as $application) {

            $resource = '@CanalTP' . $application . 'BusinessAppBundle/Resources/config/routing.yml';
            $type     = 'yaml';

            $importedRoutes = $this->import($resource,
                                            $type);
            //$appRoutes : business routes, but redirect all to sam controller
            //$importedRoutes : business routes renamed, but still with the good business controller
            $appRoutes = clone $importedRoutes;
//            $appRoutes->addDefaults(array('_controller' => 'CanalTPSamBundle:Sam:AppRender'));
            $appRoutes->addPrefix('/'. strtolower($application));

//            foreach ($importedRoutes as $routeName => $route) {
//                $importedRoutes->add('sam_' . $this->routePrefix . '_' . $routeName, clone $route);
//                $importedRoutes->remove($routeName);
//            }

//            $importedRoutes->addPrefix('/' . $this->routePrefix . '-'. strtolower($application));
//            $collection->addCollection($importedRoutes);
            $collection->addCollection($appRoutes);
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'sam' === $type;
    }
}
