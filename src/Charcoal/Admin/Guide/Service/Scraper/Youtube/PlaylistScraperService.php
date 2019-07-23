<?php

namespace Charcoal\Admin\Guide\Service\Scraper\Youtube;

use Charcoal\Admin\Guide\Object\YoutubeVideo;
use Charcoal\Admin\Guide\Service\Scraper\ScraperService;
use Charcoal\Model\ModelFactoryTrait;

class PlaylistScraperService extends ScraperService
{
    use ModelFactoryTrait;

    /**
     * PlaylistScraperService constructor.
     * Custom services needed for the current scraper
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->setModelFactory($data['model/factory']);
        parent::__construct($data);
    }

    /**
     * Run the scrape service
     *
     * @param array $options
     * @return $this|mixed
     */

    public function run($options = [])
    {
        if (!isset($options['playlistId'])) {
            throw new \InvalidArgumentException('No playlist ID defined for the youtube playlist scraper.');
        }

        $playlistId = $options['playlistId'];
        $res        = $this->service()->get($playlistId);

        if (!$res || !$res->getBody()) {
            throw new \RuntimeException(sprintf('An error occured while fetching youtube playlist %s', $playlistId));
        }

        $ret = json_decode($res->getBody(), true);

        if (!isset($ret['items'])) {
            throw new \RuntimeException(sprintf('No item found for youtube playlist %s', $playlistId));
        }

        $proto = $this->modelFactory()->create(YoutubeVideo::class);
        if (!$proto->source()->tableExists()) {
            $proto->source()->createTable();
        }

        // Avoid duplicated, delete previous entries
        $q = strtr('DELETE FROM `%table`',
            [
                '%table' => $proto->source()->table()
            ]);

        // Removing all entries
        $proto->source()->dbQuery($q);

        $converter = $this->presenter();

        foreach ($ret['items'] as $item) {
            $data = $converter->transform($item);

            $obj = $this->modelFactory()->create(YoutubeVideo::class)->setData($data);
            $obj->save();

            $this->addFeedback(strtr('Imported video %id - %title', [
                '%id'    => $obj->id(),
                '%title' => $obj->title()
            ]));
        }

        return $this;
    }

}
