<?php

namespace Charcoal\Admin\Guide;

// local dependencies.
use Charcoal\Admin\Guide\Service\Api\Youtube\PlaylistService;
use Charcoal\Admin\Guide\Service\Scraper\Youtube\PlaylistScraperService;
use Charcoal\Admin\Guide\Service\VideoAssociationService;
use Charcoal\Admin\Guide\Service\YoutubePlaylistScraperService;
use Charcoal\Admin\Guide\Transformer\Youtube\PlaylistTransformer;
use Charcoal\Presenter\Presenter;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Guide Service Provider
 */
class GuideServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container Pimple DI container.
     * @return void
     */
    public function register(Container $container)
    {
        /**
         * @return GuideConfig
         */
        $container['admin/guide/config'] = function () {
            return new GuideConfig();
        };

        $this->registerTransformers($container);

        /**
         * Key is set in google cloud console.
         *
         * @return PlaylistService
         */
        $container['guide/service/youtube/playlist'] = function ($container) {

            $opts = [
                'key' => $container['config']->get('apis.google.youtube.key')
            ];

            if (!!$container['base-url']) {
                $opts['base-url'] = $container['base-url'];
            }
            return new PlaylistService($opts);
        };

        /**
         * @return PlaylistScraperService
         */
        $container['youtube/playlist/scraper'] = function ($container) {

            return new PlaylistScraperService([
                'service' => $container['guide/service/youtube/playlist'],
                'presenter' => $container['guide/service/youtube/playlist/presenter'],
                'model/factory' => $container['model/factory']
            ]);
        };

        /**
         * @return VideoAssociationService
         */
        $container['guide/video/association'] = function ($container) {

            return new VideoAssociationService([
                'model/factory' => $container['model/factory'],
                'model/collection/loader' => $container['model/collection/loader']
            ]);
        };
    }

    /**
     * Transformers are here to convert informations from the API
     * into actual data for objects
     *
     * @param Container $container
     */
    protected function registerTransformers(Container $container)
    {
        /**
         * Key is set in google cloud console.
         *
         * @return Presenter
         */
        $container['guide/service/youtube/playlist/presenter'] = function () {
            return new Presenter(new PlaylistTransformer());
        };
    }
}
