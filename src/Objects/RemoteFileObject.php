<?php

namespace RonAppleton\WebCrawler\Objects;

class RemoteFileObject
{
    private $filepath;
    private $filename;
    private $extension;
    private $web_path_to_file;
    private $clean_name;

    public function __construct($web_path_to_file, $filepath)
    {
        $this->web_path_to_file = $web_path_to_file;
        $this->filepath = $filepath;
        $this->process();
    }

    private function process()
    {
        $path_parts = explode(DIRECTORY_SEPARATOR, $this->filepath);
        $this->filename = $path_parts[count($path_parts) - 1];
        unset($path_parts);
        $filename_parts = explode('.', $this->filename);
        $this->extension = $filename_parts[count($filename_parts) - 1];
        unset($filename_parts);
    }

    public function getFilePath()
    {
        return $this->filepath;
    }

    public function getFilename($extension = true)
    {
        if ($extension) {
            return $this->filename;
        } else {
            $filename_parts = explode('.', $this->filename);
            unset($filename_parts[count($filename_parts) - 1]);
            return implode('.', $filename_parts);
        }

    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getWebPath()
    {
        return $this->web_path_to_file;
    }

    private function cleanFileName()
    {

    }
}
