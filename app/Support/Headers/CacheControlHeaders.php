<?php


namespace App\Support\Headers;


use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;

trait CacheControlHeaders
{
    /**
     * Send 304 header if page not modified since given date.
     *
     * @param $user
     * @param Response $response
     * @param Carbon $lastModified
     */
    private function checkAndSetLastModifiedHeader($user, Response $response, Carbon $lastModified)
    {
        if (!$user && request()->hasHeader('IF_MODIFIED_SINCE') && !request()->hasHeader('IF_NONE_MATCH')) {
            try {
                $lastStoredTime = Carbon::createFromTimestamp(strtotime(request()->header('IF_MODIFIED_SINCE')));

                if ($lastStoredTime >= $lastModified) {
                    header('HTTP/1.1 304 Not Modified');
                    die;
                }
            } catch (Exception $exception) {
                $response->header('Last-Modified', $lastModified->format('D, d M Y H:i:s') . ' GMT');
            }
        }

        $response->header('Last-Modified', $lastModified->format('D, d M Y H:i:s') . ' GMT');
    }

    /**
     * Send Last-Modified header.
     *
     * @param $user
     * @param Response $response
     */
    private function checkAndSetEtagHeader($user, Response $response)
    {
        $lastStoredEtag = request()->header('IF_NONE_MATCH');

        if ($lastStoredEtag || $user) {
            $currentEtag = md5($response->getContent());

            if ($lastStoredEtag && $currentEtag === $lastStoredEtag) {
                header('HTTP/1.1 304 Not Modified');
                die;
            }

            if ($user) {
                $response->header('ETag', $currentEtag);
            }
        }
    }
}
