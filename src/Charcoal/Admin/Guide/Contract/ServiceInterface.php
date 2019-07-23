<?php

namespace Charcoal\Admin\Guide\Contract;

interface ServiceInterface
{
    /**
     * Either false or RequestInterface
     *
     * @param $url
     * @return mixed
     */
    public function get($url);
}
