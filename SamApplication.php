<?php

namespace CanalTP\SamEcoreApplicationManagerBundle;

/**
 * Interface to know if a bundle is a SAM application
 */
interface SamApplication
{
    /**
     * Get canonical name
     *
     * @return string
     */
    public function getCanonicalName();
}
