<?php

namespace Charcoal\Admin\Guide\Action;

use Charcoal\Admin\Guide\Service\VideoAssociationService;
use Charcoal\App\Action\AbstractAction;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FetchObjectsAction extends AbstractAction
{
    /**
     * @var VideoAssociationService
     */
    protected $videoAssociationService;

    /**
     * @param Container $container Pimple\Container.
     * @return void
     */
    public function setDependencies(Container $container)
    {
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
     * @return FetchObjectsAction
     */
    public function setVideoAssociationService($videoAssociationService)
    {
        $this->videoAssociationService = $videoAssociationService;
        return $this;
    }

    /**
     * @param RequestInterface  $request  A PSR-7 compatible Request instance.
     * @param ResponseInterface $response A PSR-7 compatible Response instance.
     * @return ResponseInterface
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParams();
        $this->setMode('json');

        return $response;
    }

    public function error($message)
    {
        $this->setMode('json');
        $this->feedback = $message;
    }

    /**
     * @return array|mixed
     */
    public function results()
    {
        return $this->videoAssociationService()->associations();
    }
}
