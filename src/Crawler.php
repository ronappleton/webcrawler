<?php

namespace RonAppleton\WebCrawler;

use RonAppleton\WebCrawler\Components\Client;
use RonAppleton\WebCrawler\Objects\RemoteDirectoryObject;
use RonAppleton\WebCrawler\Objects\RemoteFileObject;
use RonAppleton\WebCrawler\Objects\WebPage;
use Carbon\Carbon;

class Crawler
{
    private $pages = [];
    private $crawl_start;
    private $crawl_end;
    private $crawl_time;

    public function crawl($url = '', $restrict_domain = true, $ignore_web_files = true)
    {
        if (empty($url)) {
            return null;
        }

        $page = new WebPage($url);

        $this->crawl_start = Carbon::now();

        $dom_document = new \DOMDocument();

        $html = Client::getPage($page->url());

        if (empty($html)) {
            return;
        }

        // Suppress annoying markup warnings
        libxml_use_internal_errors(true);

        $dom_document->loadHTML($html);

        $links = $dom_document->getElementsByTagName('a');

        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (!contains($href, '..')) {
                if (is_remote_file($href) && !is_web_file($href)) {
                    cli_message('Remote File! - ' . $href);
                    $page->addFile(new RemoteFileObject($url, $href));
                } elseif ((!contains($href, 'http') || contains($href, 'https')) && !is_a_remote_file($href)) {
                    cli_message('Remote Directory - ' . $href);
                    $page->addDirectory(new RemoteDirectoryObject($url, $href));
                }
            }
        }

        $this->pages[] = $page;

        foreach ($page->getDirectories() as $directory) {
            if ($restrict_domain) {
                if (!contains($directory->getPath(), $url)) {
                    continue;
                }
            }
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

    public function logCrawling($filepath = null, $filename = null, $directories = true, $files = true, $json = true, $pretty = true)
    {
        if (empty($this->pages)) {
            return null;
        }

        if (empty($filename)) {
            $filename = date('Y_m_d__H_m_s__') . 'site_crawl.json';
        }

        if (empty($filepath)) {
            $fullpath = $filename;
        } else {
            if ($filepath[strlen($filepath)] !== DIRECTORY_SEPARATOR) {
                $fullpath = implode(DIRECTORY_SEPARATOR, [$filepath, $filename]);
            }
            else {
                $fullpath = $filepath . $filename;
            }
        }

        $handle = fopen($fullpath, 'w');

        if ($json) {
            fwrite($handle, $this->toJson($directories, $files,$pretty));
        } else {
            fwrite($handle, serialize($this->toArray($directories, $files)));
        }


        fclose($handle);

        return true;
    }

}
