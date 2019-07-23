<?php

namespace Charcoal\Admin\Guide\Object;

use Charcoal\Object\Content;

class VideoAssociation extends Content
{
    /**
     * Target object type
     *
     * @var string
     */
    protected $targetObjType;

    /**
     * Target object property
     *
     * @var mixed
     */
    protected $targetObjProperty;

    /**
     * Target object property value
     *
     * @var mixed
     */
    protected $targetObjPropertyValue;

    /**
     * Form | Table
     *
     * @var string
     */
    protected $targetWidget;

    /**
     * Video ID
     *
     * @var mixed
     */
    protected $video;

    /**
     * In case of custom video input
     *
     * @var string
     */
    protected $customVideo;

    /**
     * Youtube | Vimeo
     *
     * @var string
     */
    protected $videoType;

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
    public function targetObjType()
    {
        return $this->targetObjType;
    }

    /**
     * @param string $targetObjType
     * @return VideoAssociation
     */
    public function setTargetObjType($targetObjType)
    {
        $this->targetObjType = $targetObjType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function targetObjProperty()
    {
        return $this->targetObjProperty;
    }

    /**
     * @param mixed $targetObjProperty
     * @return VideoAssociation
     */
    public function setTargetObjProperty($targetObjProperty)
    {
        $this->targetObjProperty = $targetObjProperty;
        return $this;
    }

    /**
     * @return mixed
     */
    public function targetObjPropertyValue()
    {
        return $this->targetObjPropertyValue;
    }

    /**
     * @param mixed $targetObjPropertyValue
     * @return VideoAssociation
     */
    public function setTargetObjPropertyValue($targetObjPropertyValue)
    {
        $this->targetObjPropertyValue = $targetObjPropertyValue;
        return $this;
    }

    /**
     * @return string
     */
    public function targetWidget()
    {
        return $this->targetWidget;
    }

    /**
     * @param string $targetWidget
     * @return VideoAssociation
     */
    public function setTargetWidget($targetWidget)
    {
        $this->targetWidget = $targetWidget;
        return $this;
    }

    /**
     * @return mixed
     */
    public function video()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     * @return VideoAssociation
     */
    public function setVideo($video)
    {
        $this->video = $video;
        return $this;
    }

    /**
     * @return string
     */
    public function customVideo()
    {
        return $this->customVideo;
    }

    /**
     * @param string $customVideo
     * @return VideoAssociation
     */
    public function setCustomVideo($customVideo)
    {
        $this->customVideo = $customVideo;
        return $this;
    }

    /**
     * @return string
     */
    public function videoType()
    {
        return $this->videoType;
    }

    /**
     * @param string $videoType
     * @return VideoAssociation
     */
    public function setVideoType($videoType)
    {
        $this->videoType = $videoType;
        return $this;
    }

}
