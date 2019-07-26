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
    public function loadAssociations($targetObjType=null)
    {
        $proto = $this->modelFactory()->create(VideoAssociation::class);
        if (!$proto->source()->tableExists()) {
            $proto->source()->createTable();
        }
        $loader = $this->collectionLoader()->setModel($proto)->addOrder('position', 'asc');

        if ($targetObjType !== null) {
            $loader->addFilter('target_obj_type', $targetObjType);
        }
        $list = $loader->load();

        $processedObjTypes = [];
        $usedIds           = [
            'form'  => [],
            'table' => []
        ];

        $i = 0;
        foreach ($list as $obj) {
            $objType = $obj->targetObjType();
            $video   = $this->modelFactory()->create(YoutubeVideo::class)->load($obj->video());
            $target  = $this->modelFactory()->create($objType);

            $q = 'SELECT %key FROM `%table`';
            $values = [
                '%table' => $target->source()->table(),
                '%key' => $target->key()
            ];

            $loader  = $this->collectionLoader()->reset()->setModel($target);
            if ($obj->targetObjPropertyValue()) {
                $q .= ' WHERE %property = \'%value\'';
                $values['%property'] = $obj->targetObjProperty();
                $values['%value'] = $obj->targetObjPropertyValue();
                $loader->addFilter($obj->targetObjProperty(), $obj->targetObjPropertyValue());
            }


            $ident = $obj->targetObjPropertyValue() ?: 'default';
            if (!isset($processedObjTypes[$objType][$obj->targetWidget()][$ident])) {
                $processedObjTypes[$objType][$obj->targetWidget()][$ident] = [
                    'video' => $this->formatVideo($video),
                    'ids'   => []
                ];
            }
            if ($targetObjType !== null) {
                $q   = strtr($q, $values);
                $res = $target->source()->dbQuery($q);
                foreach ($res as $o) {
                    if (!isset($usedIds[$objType])) {
                        $usedIds[$objType] = [];
                    }
                    if (!isset($usedIds[$objType][$obj->targetWidget()])) {
                        $usedIds[$objType][$obj->targetWidget()] = [];
                    }
                    if (!$obj->targetObjPropertyValue() || !in_array($o[0], $usedIds[$objType][$obj->targetWidget()])) {
                        $usedIds[$objType][$obj->targetWidget()][]                          = $o[0];
                        $processedObjTypes[$objType][$obj->targetWidget()][$ident]['ids'][] = $o[0];
                    }
                }
                unset($usedIds[$objType]);
            }

        }


        return $processedObjTypes;
    }

    /**
     * @return array
     */
    public function associations($objType=null)
    {
        if (!$this->associations) {
            $this->associations = $this->loadAssociations($objType);
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
