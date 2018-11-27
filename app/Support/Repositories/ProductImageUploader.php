<?php
/**
 * Product image uploader.
 */

namespace App\Support\Repositories;


use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Storage;

class ProductImageUploader
{
    /**
     * @param array $images
     * @throws \Exception
     * @throws \Throwable
     */
    public function uploadImages(array $images)
    {
        $client = $this->getClient();

        // create promises
        $promises = [];

        foreach ($images as $filePath => $url) {
            $promises[$filePath] = $client->getAsync($url);
        }

        // execute requests
        $results = Promise\unwrap($promises);
$i=0;
        // store uploaded images
        foreach ($results as $filePath => $response) {
            if ($i>0) break;
            echo $i . '---' . $filePath;
            echo "\n";
            $i++;
            try {
                Storage::disk('public')->put($filePath, $response->getBody()->read(100000));
            }catch (\Exception $exception){
                echo $exception->getMessage();
            }
        }

    }

    /**
     * Get Guzzle client.
     *
     * @return Client
     */
    private function getClient()
    {
        return new Client();
    }
}