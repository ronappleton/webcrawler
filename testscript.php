<?php

require ('vendor/autoload.php');

use RonAppleton\WebCrawler\Crawler;

class Runner {

    private $urls = [
        "http://dl.upload8.net/Serial/"
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
