<?php


namespace Charcoal\Admin\Guide\Service\Api\Youtube;

use Charcoal\Admin\Guide\Contract\ServiceInterface;

class PlaylistService extends AbstractYoutubeService implements
    ServiceInterface
{
    const VERB = 'playlistItems';

    /**
     * @param $playlistId
     * @return bool|mixed
     */
    public function get($playlistId)
    {
        $scope = self::GOOGLE_API_SCOPE;
        $verb  = self::VERB;
        $part  = 'snippet';
        $key   = $this->apiKey();

        $url = filter_var(strtr(
            '%scope%verb?part=%part&playlistId=%playlistId&key=%key&maxResults=50',
            [
                '%scope'      => $scope,
                '%verb'       => $verb,
                '%playlistId' => $playlistId,
                '%part'       => $part,
                '%key'        => $key
            ]
        ), FILTER_SANITIZE_URL);

        $response = $this->fetch($url);

        return $response;
    }
}
