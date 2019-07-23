<?php

namespace Charcoal\Admin\Guide;

// from charcoal-app
use Charcoal\App\Module\AbstractModule;

/**
 * Guide Module
 */
class GuideModule extends AbstractModule
{
    const ADMIN_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-guide/config/admin.json';
    const APP_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-guide/config/config.json';

    /**
     * Setup the module's dependencies.
     *
     * @return GuideModule
     */
    public function setup()
    {
        $container = $this->app()->getContainer();

        $helpServiceProvider = new GuideServiceProvider();
        $container->register($helpServiceProvider);

        $helpConfig = $container['admin/guide/config'];
        $this->setConfig($helpConfig);

        return $this;
    }
}
