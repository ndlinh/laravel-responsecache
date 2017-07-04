<?php

namespace Spatie\ResponseCache\CacheProfiles;

use DateTime;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface CacheProfile
{
    /*
     * Determine if the response cache middleware should be enabled.
     */
    public function enabled(Request $request);

    /*
     * Determine if the given request should be cached.
     */
    public function shouldCacheRequest(Request $request);

    /*
     * Determine if the given response should be cached.
     */
    public function shouldCacheResponse(Response $response);

    /*
     * Return the time when the cache must be invalidated.
     */
    public function cacheRequestUntil(Request $request);

    /*
     * Return a string to differentiate this request from others.
     *
     * For example: if you want a different cache per user you could return the id of
     * the logged in user.
     */
    public function cacheNameSuffix(Request $request);
}
