<?php

namespace Charcoal\Admin\Guide\Template;

use Charcoal\Admin\AdminTemplate;
use Charcoal\Admin\Guide\Object\YoutubeVideo;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;

class ScrapeVideoTemplate extends BaseVideoTemplate
{
    const SECONDARY_MENU_IDENT = 'guide/scrape-video';

    /**
     * @return \Charcoal\Admin\Widget\SecondaryMenuWidgetInterface|null
     */
    public function secondaryMenu()
    {
        $this['secondary_menu_item'] = self::SECONDARY_MENU_IDENT;
        return parent::secondaryMenu();
    }

}
