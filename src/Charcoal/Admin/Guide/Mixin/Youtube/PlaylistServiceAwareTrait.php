<?php

namespace Charcoal\Admin\Guide\Youtube;

trait PlaylistServiceAwareTrait
{
    protected $playlistService;

    /**
     * @return mixed
     */
    public function playlistService()
    {
        return $this->playlistService;
    }

    /**
     * @param mixed $playlistService
     * @return PlaylistServiceAwareTrait
     */
    public function setPlaylistService($playlistService)
    {
        $this->playlistService = $playlistService;
        return $this;
    }
}
