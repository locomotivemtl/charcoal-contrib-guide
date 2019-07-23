<?php

namespace Charcoal\Admin\Guide\Object;

use Charcoal\Object\Content;

class YoutubeVideo extends Content
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $thumbnail;

    /**
     * @var string
     */
    protected $playlist;

    /**
     * @var int
     */
    protected $position;

    /**
     * @param array|null $data Dependencies.
     */
    public function __construct(array $data = null)
    {
        parent::__construct($data);

        $defaultData = $this->metadata()->defaultData();
        if ($defaultData) {
            $this->setData($defaultData);
        }
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return YoutubeVideo
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return YoutubeVideo
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function thumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     * @return YoutubeVideo
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return string
     */
    public function playlist()
    {
        return $this->playlist;
    }

    /**
     * @param string $playlist
     * @return YoutubeVideo
     */
    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
        return $this;
    }

    /**
     * @return int
     */
    public function position()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return YoutubeVideo
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

}
