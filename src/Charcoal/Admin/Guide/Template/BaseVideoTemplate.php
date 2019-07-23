<?php

namespace Charcoal\Admin\Guide\Template;

use Charcoal\Admin\AdminTemplate;
use Charcoal\Admin\Guide\Object\YoutubeVideo;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;

class BaseVideoTemplate extends AdminTemplate
{
    const MAIN_MENU_IDENT = 'admin/guide/video';


    /**
     * Template's init method is called automatically from `charcoal-app`'s Template Route.
     *
     * For admin templates, initializations is:
     *
     * - to start a session, if necessary
     * - to authenticate
     * - to initialize the template data with the PSR Request object
     *
     * @param RequestInterface $request The request to initialize.
     * @return boolean
     * @see \Charcoal\App\Route\TemplateRoute::__invoke()
     */
    public function init(RequestInterface $request)
    {
        return parent::init($request);
    }


    /**
     * @return array|\Charcoal\Admin\Generator
     */
    public function mainMenu()
    {
        $this['main_menu_item'] = self::MAIN_MENU_IDENT;
        return parent::mainMenu();
    }

}
