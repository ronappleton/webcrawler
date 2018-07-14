<?php

namespace App\Components;

use GuzzleHttp\Client as GuzzleClient;
/**
 * This class is responsible for getting the contents
 * of a given url
 *
 * Class Client
 */
class Client
{
    public static function getPage($url)
    {
        if (!empty($url)) {
            $client = new GuzzleClient();
            $result = $client->get($url);
            if (!empty($result)) {
                return $result->getBody();
            }
            return '';
        }
        return '';
    }
}
