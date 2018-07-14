<?php

namespace App\Transformers;

use App\Objects\RemoteDirectoryObject;

class RDOTransformer
{
    private $remoteDirectoryObject;

    public function __construct(RemoteDirectoryObject $remoteDirectoryObject)
    {
        $this->remoteDirectoryObject = $remoteDirectoryObject;
    }

    public function transform()
    {
        return [
            "directory_name" => $this->remoteDirectoryObject->getDirectory(),
            "remote_directory_path" => $this->remoteDirectoryObject->getPath(),
        ];
    }
}
