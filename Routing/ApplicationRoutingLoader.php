<?php

/**
 * Description of RoutingLoader
 *
 * Cette classe charge les routing Yml pour un client donné
 *
 * @author akambi
 */
namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class ApplicationRoutingLoader extends Loader
{
    private $loaded = false;
    private $aBundles;

    public function __construct($aBundles)
    {
        $this->aBundles = $aBundles;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $this->loaded = true;

        $collection = new RouteCollection();
        $aApplications = array();

        preg_match_all(
            "|\\\CanalTP(?P<applications>[^\\\]*)BusinessAppBundle|U",
            implode(',', $this->aBundles),
            $aApplications,
            PREG_PATTERN_ORDER
        );

        foreach ($aApplications['applications'] as $application) {
            $resource = '@CanalTP' . $application . 'BusinessAppBundle/Resources/config/routing.yml';
            $type     = 'yaml';

            $importedRoutes = $this->import($resource, $type);
            $importedRoutes->addPrefix('/'. strtolower($application));
            $collection->addCollection($importedRoutes);
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'sam' === $type;
    }
}
