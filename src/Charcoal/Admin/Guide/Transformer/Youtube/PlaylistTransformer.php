<?php

namespace Charcoal\Admin\Guide\Transformer\Youtube;

class PlaylistTransformer
{
    /**
     * Assumings playlistItem
     *
     * @param $data
     */
    public function __invoke($data)
    {
        if (!isset($data['snippet'])) {
            throw new \InvalidArgumentException(
                'Invalid data provided to the Youtube PlaylistTransformer'
            );
        }

        $snippet = $data['snippet'];
        return [
            'title' => $snippet['title'],
            'thumbnail' => $snippet['thumbnails']['standard']['url'],
            'position' => $snippet['position'],
            'id' => $snippet['resourceId']['videoId'],
            'playlist' => $snippet['playlistId']
        ];
    }
}
