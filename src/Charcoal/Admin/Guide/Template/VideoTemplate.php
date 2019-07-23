<?php

namespace Charcoal\Admin\Guide\Template;

use Charcoal\Admin\AdminTemplate;
use Charcoal\Admin\Guide\Object\YoutubeVideo;
use Charcoal\Loader\CollectionLoaderAwareTrait;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;

class VideoTemplate extends BaseVideoTemplate
{
    use CollectionLoaderAwareTrait;

    /**
     * @var array
     */
    protected $videos;

    /**
     * Set common dependencies (services) used in all admin templates.
     *
     * @param Container $container DI Container.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        $this->setCollectionLoader($container['model/collection/loader']);
        parent::setDependencies($container);
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
        $proto  = $this->modelFactory()->create(YoutubeVideo::class);

        if (!$proto->source()->tableExists()) {
            return $this->videos;
        }

        $loader = $this->collectionLoader()->setModel($proto);
        $loader->addOrder('position', 'asc')->addFilter('active', true);
        $list = $loader->load();

        foreach ($list as $v) {
            $this->videos[] = [
                'id' => $v->id(),
                'thumbnail' => $v->thumbnail(),
                'playlist' => $v->playlist(),
                'title' => $v->title()
            ];
        }

        return $this->videos;
    }
}
