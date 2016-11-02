<?php

namespace App\Command;

use App\Vine\UserTimeline;
use App\Vine\Video;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\Question;

class GetVideos extends Command
{
    const VINE_URI_API = 'https://api.vineapp.com';

    /**
     * @var int
     */
    private $currentPage = 1;

    /**
     * @var ProgressBar
     */
    private $progressBar;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $downloadDir;

    protected function configure()
    {
        $this
            ->setName('app:get-videos')
            ->setDescription('Get the videos.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $userIdQuestion = new Question('Please enter the id of the vine user : ');

        $userId = (int) $helper->ask($input, $output, $userIdQuestion);

        if (!$userId) {
            throw new \Exception('You must specify a valid user id');
        }

        $this->userId = $userId;

        $downloadDirQuestion = new Question('Please enter the download path : ');
        $downloadDir = (string) $helper->ask($input, $output, $downloadDirQuestion);

        if (!is_dir($downloadDir)) {
            throw new \Exception(sprintf('"%s" is not a valid directory', $downloadDir));
        }

        $this->downloadDir = $downloadDir;

        $this->client = new Client([
            'base_uri' => self::VINE_URI_API,
        ]);

        $userTimeline = $this->getUserTimeline($this->currentPage);


        $this->progressBar = new ProgressBar($output, $userTimeline->count());
        $this->progressBar->start();

        $this->downloadVideosRecursive($userTimeline);
    }

    /**
     * @param $userTimeline
     */
    private function downloadVideosRecursive(UserTimeline $userTimeline)
    {
        $this->downloadVideos($userTimeline->getVideos());

        if ($userTimeline->getNextPage() > 0) {
            $newTimeline = $this->getUserTimeline($userTimeline->getNextPage());
            $this->downloadVideosRecursive($newTimeline);
        }
    }

    /**
     * @param array $listVideo
     */
    private function downloadVideos(array $listVideo)
    {
        /** @var Video $video */
        foreach ($listVideo as $video) {
            $this->client->request('GET', $video->getUrl(), [
                'sink' => $this->downloadDir.'/'.$video->getFileName(),
            ]);

            $this->progressBar->advance();
        }
    }

    /**
     * @param int $page
     * @return UserTimeline
     */
    private function getUserTimeline($page)
    {
        $response = $this->client->get('timelines/users/'.$this->userId, [
            'query' => [
                'page' => $page,
            ],
        ]);

        $body =  \GuzzleHttp\json_decode($response->getBody()->__toString());

        return new UserTimeline($body);
    }
}
