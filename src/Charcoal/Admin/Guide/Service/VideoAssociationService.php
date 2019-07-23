<?php

namespace Charcoal\Admin\Guide\Service;

use Charcoal\Admin\Guide\Object\VideoAssociation;
use Charcoal\Admin\Guide\Object\YoutubeVideo;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Charcoal\Model\ModelFactoryTrait;

class VideoAssociationService
{
    use ModelFactoryTrait;
    use CollectionLoaderAwareTrait;

    /**
     * @var array
     */
    protected $associations;

    /**
     * VideoAssociationService constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->setModelFactory($data['model/factory']);
        $this->setCollectionLoader($data['model/collection/loader']);
    }

    /**
     *
     */
    public function loadAssociations()
    {
        $proto = $this->modelFactory()->create(VideoAssociation::class);
        if (!$proto->source()->tableExists()) {
            $proto->source()->createTable();
        }
        $list  = $this->collectionLoader()->setModel($proto)->load();

        $processedObjTypes = [];
        $usedIds           = [];

        foreach ($list as $obj) {
            $objType = $obj->targetObjType();

            $video  = $this->modelFactory()->create(YoutubeVideo::class)->load($obj->video());
            $target = $this->modelFactory()->create($objType);

            $loader = $this->collectionLoader()->reset()->setModel($target);

            if ($obj->targetObjPropertyValue()) {
                $loader->addFilter($obj->targetObjProperty(), $obj->targetObjPropertyValue());
            }

            $ident = $obj->targetObjPropertyValue() ?: 'default';

            if (!isset($processedObjTypes[$objType][$obj->targetWidget()][$ident])) {

                $processedObjTypes[$objType][$obj->targetWidget()][$ident] = [
                    'video' => $this->formatVideo($video),
                    'ids'   => []
                ];
            }

            $list = $loader->load();

            foreach ($list as $o) {
                if (!in_array($o->id(), $usedIds)) {
                    $usedIds[]                                                          = $o->id();
                    $processedObjTypes[$objType][$obj->targetWidget()][$ident]['ids'][] = $o->id();
                }
            }
        }

        return $processedObjTypes;
    }

    /**
     * @return array
     */
    public function associations()
    {
        if (!$this->associations) {
            $this->associations = $this->loadAssociations();
        }

        return $this->associations;
    }

    /**
     * @param      $objType
     * @param null $widget
     * @param null $ident
     * @return array|mixed
     */
    public function getAssociations($objType, $widget = null, $ident = null)
    {
        $associations = $this->associations();

        if (!$objType) {
            throw new \InvalidArgumentException('Missing parameters');
        }

        $out = isset($associations[$objType]) ? $associations[$objType] : [];
        if ($widget) {
            $out = isset($out[$widget]) ? $out[$widget] : [];
        }

        if ($ident) {
            $out = isset($out[$ident]) ? $out[$ident] : [];
        }

        return $out;
    }

    /**
     * @param YoutubeVideo $video
     * @return array
     */
    public function formatVideo(YoutubeVideo $video)
    {
        return [
            'id'        => $video->id(),
            'title'     => $video->title(),
            'thumbnail' => $video->thumbnail(),
            'playlist'  => $video->playlist()
        ];
    }
}
