<?php

namespace Charcoal\Admin\Guide\Template;

use Charcoal\Admin\Guide\Object\YoutubeVideo;
use Charcoal\Admin\Guide\Service\VideoAssociationService;
use Charcoal\Cms\TemplateableInterface;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Charcoal\Translator\TranslatorAwareTrait;
use Pimple\Container;

class AssociateVideoTemplate extends BaseVideoTemplate
{
    use CollectionLoaderAwareTrait;
    use TranslatorAwareTrait;

    const SECONDARY_MENU_IDENT = 'guide/associate-video';

    /**
     * @var array
     */
    protected $videos;

    /**
     * @var array
     */
    protected $objTypes;

    /**
     * @var VideoAssociationService
     */
    protected $videoAssociationService;

    /**
     * Set common dependencies (services) used in all admin templates.
     *
     * @param Container $container DI Container.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        $this->setCollectionLoader($container['model/collection/loader']);
        $this->appConfig = $container['config'];
        $this->setTranslator($container['translator']);
        $this->setVideoAssociationService($container['guide/video/association']);

        parent::setDependencies($container);
    }

    /**
     * @return VideoAssociationService
     */
    public function videoAssociationService()
    {
        return $this->videoAssociationService;
    }

    /**
     * @param VideoAssociationService $videoAssociationService
     * @return AssociateVideoTemplate
     */
    public function setVideoAssociationService($videoAssociationService)
    {
        $this->videoAssociationService = $videoAssociationService;
        return $this;
    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    protected function getVideosWithSelected($id)
    {
        $out    = [];
        $videos = $this->videos();

        foreach ($videos as $v) {
            if ($v['id'] === $id) {
                $v['selected'] = true;
            }
            $out[] = $v;
        }
        return $out;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function hasVideos()
    {
        return !!count($this->videos());
    }

    /**
     * Youtube videos.
     *
     * @return array
     * @throws \Exception
     */
    public function videos()
    {
        if ($this->videos) {
            return $this->videos;
        }

        $this->videos = [];
        $proto        = $this->modelFactory()->create(YoutubeVideo::class);

        if (!$proto->source()->tableExists()) {
            return $this->videos;
        }

        $loader = $this->collectionLoader()->setModel($proto);
        $loader->addOrder('position', 'asc')->addFilter('active', true);
        $list = $loader->load();

        foreach ($list as $v) {
            $this->videos[] = [
                'id'        => $v->id(),
                'thumbnail' => $v->thumbnail(),
                'playlist'  => $v->playlist(),
                'title'     => $v->title(),
                'selected'  => false
            ];
        }

        return $this->videos;
    }

    /**
     * Supported widgets
     *
     * @return array
     */
    public function widgetTypes($ident = null)
    {
        return [
            [
                'value'    => 'form',
                'label'    => 'Form',
                'selected' => ($ident === 'form')
            ],
            [
                'value'    => 'table',
                'label'    => 'Table / Collection',
                'selected' => ($ident === 'table')
            ]
        ];
    }

    /**
     * @return array
     */
    public function objTypes()
    {
        if (!$this->objTypes) {
            $this->objTypes = $this->fetchObjectFromMenu();
        }
        return $this->objTypes;
    }

    /**
     * @param array $objTypes
     * @return AssociateVideoTemplate
     */
    public function setObjTypes($objTypes)
    {
        $this->objTypes = $objTypes;
        return $this;
    }

    /**
     * @return array
     */
    protected function fetchObjectFromMenu()
    {
        $menu    = $this->appConfig->get('admin.secondary_menu');
        $objects = [];

        foreach ($menu as $ident => $group) {
            $objects = $this->pushArrayToArray($objects, $this->fetchObjectsFromSecondaryMenu($group));
        }

        $validTypes = [];
        foreach ($objects as $obj) {
            $type     = $obj['type'];
            $label    = $type;
            $property = false;

            try {
                $proto = $this->modelFactory()->create($type);
                if (isset($proto->metadata()->labels['name'])) {
                    $label = (string)$this->translator()->translation($proto->metadata()->labels['name']);
                }

                if ($proto instanceof TemplateableInterface) {
                    $property = 'template_ident';
                }

            } catch (\Exception $e) {
                continue;
            }


            $validTypes[] = [
                'label'          => $label,
                'type'           => $type,
                'property'       => $property,
                'propertyValues' => $this->propertyValues($proto),
                'selected'       => false
            ];
        }

        return $validTypes;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function form()
    {
        $objTypes = $this->objTypes();

        $out = [];

        foreach ($objTypes as $o) {
            $tmp = [
                'label'   => $o['label'],
                'type'    => $o['type'],
                'filters' => []
            ];

            $forms  = $this->videoAssociationService()->getAssociations($o['type'], 'form');
            $tables = $this->videoAssociationService()->getAssociations($o['type'], 'table');

            $propertyValues = $o['propertyValues'];
            foreach ($forms as $ident => $f) {
                $video = $f['video']['id'];

                $pV = [];
                foreach ($propertyValues as $p) {
                    $p['selected'] = ($p['value'] === $ident);
                    $pV[]          = $p;
                }

                $videos           = $this->getVideosWithSelected($video);
                $widget           = $this->widgetTypes('form');
                $tmp['filters'][] = [
                    'widgetTypes'    => $widget,
                    'videos'         => $videos,
                    'propertyValues' => $pV,
                    'property'       => $o['property'],
                    'removable'      => true
                ];
            }

            foreach ($tables as $ident => $f) {
                $video = $f['video']['id'];

                $videos           = $this->getVideosWithSelected($video);
                $widget           = $this->widgetTypes('table');
                $tmp['filters'][] = [
                    'widgetTypes'    => $widget,
                    'videos'         => $videos,
                    'propertyValues' => $propertyValues,
                    'property'       => $o['property'],
                    'removable'      => true
                ];
            }

            $tmp['filters'][] = [
                'widgetTypes'    => $this->widgetTypes(),
                'videos'         => $this->videos(),
                'propertyValues' => $propertyValues,
                'property'       => $o['property'],
                'removable'      => false
            ];

            $out[] = $tmp;
        }

        return $out;
    }

    /**
     * @param $obj
     * @return array
     */
    protected function propertyValues($obj, $property = 'template_ident')
    {
        $properties = [];
        if ($obj instanceof TemplateableInterface) {
            $choices = $obj->p($property)->choices();
            foreach ($choices as $key => $content) {
                $properties[] = $content;
            }
        }

        return $properties;
    }

    /**
     * Check for the objType in the secondary menu items
     * returning true as soon as it its.
     *
     * @param string      $objType The ObjType to search.
     * @param array|mixed $item    The secondary menu item to search in.
     * @return boolean
     */
    protected function fetchObjectsFromSecondaryMenu($item)
    {
        $types = [];
        if (isset($item['links'])) {
            foreach ($item['links'] as $obj => $i) {
                $types[] = [
                    'type'  => $obj,
                    'label' => (string)$this->translator()->translation($i['name']),
                    'url'   => $i['url']
                ];
            }
        }

        if (isset($item['groups'])) {
            foreach ($item['groups'] as $group) {
                $merge = $this->fetchObjectsFromSecondaryMenu($group);
                $types = $this->pushArrayToArray($types, $merge);
            }
        }

        return $types;
    }

    /**
     * Helper to merge arrays
     *
     * @param $source
     * @param $push
     * @return array
     */
    protected function pushArrayToArray($source, $push)
    {
        foreach ($push as $p) {
            $source[] = $p;
        }

        return $source;
    }

    /**
     * @return \Charcoal\Admin\Widget\SecondaryMenuWidgetInterface|null
     */
    public function secondaryMenu()
    {
        $this['secondary_menu_item'] = static::SECONDARY_MENU_IDENT;
        return parent::secondaryMenu();
    }
}
