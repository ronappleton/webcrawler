<?php

namespace RonAppleton\WebCrawler\Objects;

class RemoteDirectoryObject
{
    private $protocol;
    private $base_url;
    private $directory;
    private $path;

    public function __construct($base_url, $directory)
    {
        $this->base_url = $base_url;
        $this->directory = $directory;

        $this->process();
    }

    private function process()
    {
        $this->removeProtocol();
        $base_url = explode('/', $this->base_url);
        foreach($base_url as $key => $value) {
            if (empty($value)) {
                unset($base_url[$key]);
            }
        }
        $this->base_url = implode('/', $base_url);

        $this->path = $this->protocol . $this->base_url;

        $this->directory = str_replace('/', '', $this->directory);

        $this->path = implode('/', [$this->path, $this->directory]);
    }

    private function removeProtocol()
    {
        $protocols = [ 'http://', 'https://'];

        foreach ($protocols as $protocol) {
            if(contains($this->base_url, $protocol)) {
                $this->base_url = str_replace($protocol, '', $this->base_url);
                $this->protocol = $protocol;
            }
        }
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getPath()
    {
        return $this->path;
    }
}
