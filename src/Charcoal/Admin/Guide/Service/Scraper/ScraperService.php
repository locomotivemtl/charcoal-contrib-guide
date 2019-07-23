<?php

namespace Charcoal\Admin\Guide\Service\Scraper;

use Charcoal\Presenter\Presenter;

abstract class ScraperService
{
    /**
     * @var mixed
     */
    protected $service;

    /**
     * @var Presenter
     */
    protected $presenter;

    /**
     * Keep track of the runned processes
     *
     * @var array
     */
    protected $feedback = [];

    /**
     * ScraperService constructor.
     * All scraper service require a service and a presenter
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->setService($data['service']);
        $this->setPresenter($data['presenter']);
    }

    /**
     * Options can be passed such as playlistId, etc.
     *
     * @param array $options
     * @return mixed
     */
    abstract function run($options = []);

    /**
     * @return string
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * @param string $service
     * @return ScraperService
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return Presenter
     */
    public function presenter()
    {
        return $this->presenter;
    }

    /**
     * @param Presenter $presenter
     * @return ScraperService
     */
    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
        return $this;
    }

    /**
     * @return array
     */
    public function feedback()
    {
        return $this->feedback;
    }

    /**
     * @param array $feedback
     * @return ScraperService
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
        return $this;
    }

    /**
     * @param $feedback
     */
    public function addFeedback($feedback)
    {
        if (is_string($feedback)) {
            $feedback = [
                'Message' => $feedback
            ];
        }
        $this->feedback[] = $feedback;
    }
}
