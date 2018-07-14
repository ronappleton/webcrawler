<?php

namespace App\Transformers;

use App\Objects\RemoteFileObject;

class RFOTransformer
{
    private $remoteFileObject;

    public function __construct(RemoteFileObject $remoteFileObject)
    {
        $this->remoteFileObject = $remoteFileObject;
    }

    public function transform()
    {
        return [
            "remote_file_name" => $this->remoteFileObject->getFilename(),
            "remote_file_path" => implode('/', [$this->remoteFileObject->getWebPath(),$this->remoteFileObject->getFilePath()]),
        ];
    }
}
