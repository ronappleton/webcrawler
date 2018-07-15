<?php

require ('vendor/autoload.php');

use App\Crawler;

class Runner {

    private $urls = [
        "http://google.com"
    ];

    public function run() {
        foreach ($this->urls as $url) {
            $crawler = new Crawler();
            $crawler->crawl($url);
            $crawler->logCrawling();
        }
    }
}


$go = new runner();

$go->run();
