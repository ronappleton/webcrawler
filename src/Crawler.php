<?php

namespace App;

use App\Components\Client;
use App\Objects\RemoteDirectoryObject;
use App\Objects\RemoteFileObject;
use App\Objects\WebPage;
use Carbon\Carbon;

class Crawler
{
    private $pages = [];
    private $crawl_start;
    private $crawl_end;
    private $crawl_time;

    public function crawl($url = '')
    {
        if (empty($url)) {
            return null;
        }

        $page = new WebPage($url);
        $this->crawl_start = Carbon::now();

        $dom_document = new \DOMDocument();

        $dom_document->loadHTML(Client::getPage($page->url()));

        $links = $dom_document->getElementsByTagName('a');

        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (!contains($href, '..')) {
                if (is_remote_file($href)) {
                    cli_message('Remote File! - ' . $href);
                    $page->addFile(new RemoteFileObject($url, $href));
                } else {
                    cli_message('Remote Directory - ' . $href);
                    $page->addDirectory(new RemoteDirectoryObject($url, $href));
                }
            }
        }

        $this->pages[] = $page;

        foreach ($page->getDirectories() as $directory) {
            $this->crawl($directory->getPath());
        }
        $this->crawl_end = Carbon::now();
        $this->crawl_time = $this->crawl_end->diffForHumans($this->crawl_start);
    }

    public function getPages()
    {
        foreach ($this->pages as $page) {
            yield $page;
        }
    }

    public function toArray($directories = true, $files = true, $timings = true) {
        $output_array = [];

        if ($timings) {
            $output_array["timings"] = [
                'crawl_start' => $this->crawl_start,
                'crawl_end' => $this->crawl_end,
                'crawl_time' => $this->crawl_time,
            ];

        }

        foreach ($this->getPages() as $page) {
            $output_array = array_merge($output_array, $page->toArray($directories, $files));
        }
        return $output_array;
    }

    public function toJson($directories = true, $files = true, $pretty = true) {
        if ($pretty) {
            return json_encode($this->toArray($directories, $files), JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->toArray($directories, $files));
        }

    }

}
