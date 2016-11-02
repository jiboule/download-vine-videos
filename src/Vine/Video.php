<?php

namespace App\Vine;

class Video
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTime
     */
    private $created;
    
    public function __construct($videoData)
    {
        $this->url = $videoData->videoUrl;
        $this->created = new \DateTime($videoData->created);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getFileName()
    {
        return 'video-'.$this->created->getTimestamp().'.mp4';
    }
}