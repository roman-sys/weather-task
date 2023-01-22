<?php
namespace App\Communication;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

trait HttpClientApi
{

    public function sendMultiplyFor(array $api): array
    {
        $client = new Client([
            'verify' => false
        ]);

        $promises = [];
        foreach ($api as $keyName => $apiOne) {
            $promises[$keyName] = $client->getAsync($apiOne->getUrl(), $apiOne->getOptions());
        }

        $response = Promise\all($promises);
        $response = Promise\settle($promises)->wait();

        foreach ($api as $keyName => $apiOne) {
            if (isset($response[$keyName])) {
                if ($response[$keyName]['state'] === 'fulfilled') {
                    $apiOne->setLoadData(\GuzzleHttp\json_decode($response[$keyName]['value']->getBody(), true));
                    if ($apiOne->isValidData()) {
                        continue;
                    }
                }
            }
            unset($api[$keyName]);
        }

        return $api;
    }
}