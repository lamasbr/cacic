<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 08/01/15
 * Time: 17:32
 */

namespace Cacic\MultiBundle\Site;


class SiteManager {
    private $currentSite;

    public function getCurrentSite()
    {
        return $this->currentSite;
    }

    public function setCurrentSite($currentSite)
    {
        $this->currentSite = $currentSite;
    }
} 