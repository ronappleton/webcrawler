<?php

namespace RonAppleton\WebCrawler\Objects;

use RonAppleton\WebCrawler\Transformers\RDOTransformer;
use RonAppleton\WebCrawler\Transformers\RFOTransformer;

class WebPage
{
    /**
     * This will hold the upper most url of the web page
     * it universal web accessible location.
     * It is from this url that the directories below will
     * be investigated.
     *
     * @var string
     */
    private $base_url = '';

    /**
     * This will hold the files we find on this page.
     */
    private $files = [];

    /**
     * This will hold the directories we find on this page
     */
    private $directories = [];

    /**
     * This holds the pages html content.
     */
    private $content = '';

    public function __construct($base_url = '')
    {
        $this->base_url = $base_url;
    }

    public function url()
    {
        return $this->base_url;
    }

    public function content($page = null)
    {
        if (empty($page)) {
            return $this->content;
        } else {
            $this->content = $page;
        }
    }

    public function addFile(RemoteFileObject $file)
    {
        $this->files[] = $file;
    }

    public function getFiles()
    {
        foreach ($this->files as $file) {
            yield $file;
        }
    }

    public function addDirectory(RemoteDirectoryObject $directory)
    {
        $this->directories[] = $directory;
    }

    public function getDirectories()
    {
        foreach ($this->directories as $directory) {
            yield $directory;
        }
    }

    public function dumpFiles()
    {
        print_r($this->files);
    }

    public function dumpDirectories()
    {
        print_r($this->directories);
    }

    public function dump()
    {
        $this->dumpDirectories();
        $this->dumpFiles();
    }

    public function toArray($directories = false, $files = true)
    {
         $array[$this->base_url] = [];

        if ($directories) {
            $array[$this->base_url]['directory_count'] = count($this->directories);
            foreach ($this->getDirectories() as $directory) {
                $array[$this->base_url]["directories"][] = (new RDOTransformer($directory))->transform();
            }
        }
        if ($files) {
            $array[$this->base_url]['file_count'] = count($this->files);
            foreach ($this->getFiles() as $file) {
                $array[$this->base_url]["files"][] = (new RFOTransformer($file))->transform();
            }
        }

        return $array;
    }

    public function toJson($directories = false, $files = true, $pretty = false)
    {
        if ($pretty) {
            return json_encode($this->toArray($directories, $files), JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->toArray($directories, $files));
        }

    }
}
