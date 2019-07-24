<?php

namespace Charcoal\Admin\Guide\Action;

use Charcoal\Admin\Guide\Object\VideoAssociation;
use Charcoal\App\Action\AbstractAction;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Charcoal\Model\ModelFactoryTrait;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class SaveVideoAssociationAction extends AbstractAction
{
    use ModelFactoryTrait;
    use CollectionLoaderAwareTrait;

    /**
     * @param Container $container Pimple\Container.
     * @return void
     */
    public function setDependencies(Container $container)
    {
        $this->setModelFactory($container['model/factory']);
        $this->setCollectionLoader($container['model/collection/loader']);
        parent::setDependencies($container);
    }


    /**
     * @param RequestInterface  $request  A PSR-7 compatible Request instance.
     * @param ResponseInterface $response A PSR-7 compatible Response instance.
     * @return ResponseInterface
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParams();


        if (!isset($params['obj'])) {
            return $response->withStatus(404);
        }


        $objects = $params['obj'];
        $proto   = $this->modelFactory()->create(VideoAssociation::class);

        if (!$proto->source()->tableExists()) {
            $proto->source()->createTable();
        }

        $q = strtr('DELETE FROM `%table`', ['%table' => $proto->source()->table()]);
        $proto->source()->dbQuery($q);

        foreach ($objects as $objType => $val) {
            $widget = $val['widget_type'];

            $property = isset($val['property']) ? $val['property'] : '';
            $video    = $val['video'];

            $count = count($widget);
            $i     = 0;
            for (; $i < $count; $i++) {
                if (!isset($widget[$i]) || !isset($video[$i])) {
                    continue;
                }

                // These need values
                if (!$widget[$i] || !$video[$i]) {
                    continue;
                }

                $targetObjProperty      = isset($property[$i]) ? $property[$i] : '';
                $targetObjPropertyValue = ($targetObjProperty && isset($val[$targetObjProperty][$i])) ? $val[$targetObjProperty][$i] : '';

                $data        = [
                    'video'                  => $video[$i],
                    'targetWidget'           => $widget[$i],
                    'targetObjType'          => $objType,
                    'targetObjProperty'      => $targetObjProperty,
                    'targetObjPropertyValue' => $targetObjPropertyValue,
                    'position'               => $i
                ];
                $association = $this->modelFactory()->create(VideoAssociation::class);
                $association->setData($data);

                $association->save();
            }

        }

        $this->setMode('redirect');
        $this->setSuccessUrl('/admin/guide/associate-video');
        $this->setFailureUrl('/admin/guide/associate-video');
        return $response;
    }


    /**
     * @return array|mixed
     */
    public function results()
    {
        return [
        ];
    }
}
