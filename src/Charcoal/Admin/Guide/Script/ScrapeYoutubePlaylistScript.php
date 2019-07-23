<?php

namespace Charcoal\Admin\Guide\Script;

use Charcoal\Admin\Guide\Service\Scraper\Youtube\PlaylistScraperService;
use Charcoal\App\Script\AbstractScript as CharcoalScript;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Charcoal\Model\ModelFactoryTrait;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 */
class ScrapeYoutubePlaylistScript extends CharcoalScript
{
    use ModelFactoryTrait;
    use CollectionLoaderAwareTrait;

    /**
     * @var mixed
     */
    protected $baseUrl;

    /**
     * @var PlaylistScraperService
     */
    protected $youtubePlaylistScraperService;

    /**
     * @param  Container $container The DI container.
     * @return void
     */
    public function setDependencies(Container $container)
    {
        $this->setModelFactory($container['model/factory']);
        $this->setCollectionLoader($container['model/collection/loader']);
        $this->setBaseUrl($container['base-url']);

        $this->setYoutubePlaylistScraperService($container['youtube/playlist/scraper']);


        parent::setDependencies($container);
    }

    /**
     * @return mixed
     */
    public function baseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param mixed $baseUrl
     * @return ScrapeYoutubePlaylistScript
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return PlaylistScraperService
     */
    public function youtubePlaylistScraperService()
    {
        return $this->youtubePlaylistScraperService;
    }

    /**
     * @param PlaylistScraperService $youtubePlaylistScraperService
     * @return ScrapeYoutubePlaylistScript
     */
    public function setYoutubePlaylistScraperService($youtubePlaylistScraperService)
    {
        $this->youtubePlaylistScraperService = $youtubePlaylistScraperService;
        return $this;
    }


    /**
     * Valid arguments:
     * - file : path/to/csv.csv
     *
     * @return array
     */
    public function defaultArguments()
    {
        $arguments = [
            'playlist' => [
                'prefix'      => 'l',
                'longPrefix'  => 'playlist',
                'description' => 'Youtube playlist ID'
            ]
        ];

        $arguments = array_merge(parent::defaultArguments(), $arguments);
        return $arguments;
    }

    /**
     * Gets a psr7 request and response and returns a response.
     *
     * Called from `__invoke()` as the first thing.
     *
     * @param RequestInterface  $request  A PSR-7 compatible Request instance.
     * @param ResponseInterface $response A PSR-7 compatible Response instance.
     * @return ResponseInterface
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        unset($request);

        $playlist = $this->argOrInput('playlist');

        $scraper = $this->youtubePlaylistScraperService();

        try {
            $scraper->run(['playlistId' => $playlist]);
        } catch (\Exception $e) {
            $this->climate()->error('An error occured: ' . $e->getMessage());
            return $response->withStatus(404);
        }

        $this->climate()->table($scraper->feedback());

        return $response;
    }
}
