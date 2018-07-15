<?php

namespace App\Components;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

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
        $result = null;

        if (!empty($url)) {
            $client = new GuzzleClient();
            try {
                $result = $client->get($url);
            } catch (ClientException $e)
            {
                // Keep on running please..
            }

            if (!empty($result)) {
                return $result->getBody();
            }

            return '';
        }

        return '';
    }
}
