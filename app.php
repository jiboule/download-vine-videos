<?php

require_once 'vendor/autoload.php';

function ddump($m) {
    dump($m);exit();
}

use Symfony\Component\ClassLoader\Psr4ClassLoader;
use Symfony\Component\Console\Application;

$loader = new Psr4ClassLoader();
$loader->addPrefix('App', __DIR__.'/src');
$loader->register();

$application = new Application();

$application->add(new \App\Command\GetVideos());

$application->run();
