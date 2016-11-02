<?php

namespace App\Vine;

class UserTimeline
{
    private $videos = [];

    private $count;

    private $nextPage;

    public function __construct(\stdClass $userTimeline)
    {
        if (!property_exists($userTimeline, 'data')) {
            throw new \Exception('Invalid user timeline response');
        }

        $data = $userTimeline->data;

        $this->count = $data->count;
        $this->nextPage = (int) $data->nextPage;

        foreach ($data->records as $record) {
            $this->videos[] = new Video($record);
        }
    }

    public function count()
    {
        return $this->count;
    }

    public function getNextPage()
    {
        return $this->nextPage;
    }

    public function getVideos()
    {
        return $this->videos;
    }
}
