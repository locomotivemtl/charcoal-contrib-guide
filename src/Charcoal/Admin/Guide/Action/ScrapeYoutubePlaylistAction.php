<?php

namespace Charcoal\Admin\Guide\Action;

use Charcoal\App\Action\AbstractAction;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ScrapeYoutubePlaylistAction extends AbstractAction
{
    /**
     * @var array
     */
    protected $feedback;

    /**
     * @var
     */
    protected $youtubePlaylistScraperService;

    /**
     * @param Container $container Pimple\Container.
     * @return void
     */
    public function setDependencies(Container $container)
    {
        $this->setYoutubePlaylistScraperService($container['youtube/playlist/scraper']);
        parent::setDependencies($container);
    }

    /**
     * @return mixed
     */
    public function youtubePlaylistScraperService()
    {
        return $this->youtubePlaylistScraperService;
    }

    /**
     * @param mixed $youtubePlaylistScraperService
     * @return ScrapeYoutubePlaylistAction
     */
    public function setYoutubePlaylistScraperService($youtubePlaylistScraperService)
    {
        $this->youtubePlaylistScraperService = $youtubePlaylistScraperService;
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
        $this->setMode('redirect');
        $this->setSuccessUrl('/admin/guide/video');
        $this->setFailureUrl('/admin/guide/video');

        if (!isset($params['playlistId'])) {
            $this->error('No playlist ID defined');
            return $response->withStatus(404);
        }

        $playlist = $params['playlistId'];
        $scraper = $this->youtubePlaylistScraperService();

        try {
            $scraper->run(['playlistId' => $playlist]);
        } catch (\Exception $e) {
            $this->error('An error occured: ' . $e->getMessage());
            return $response->withStatus(404);
        }

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
        return [
            'success' => $this->success(),
            'message' => $this->feedback
        ];
    }
}
