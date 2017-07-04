<?php

namespace Spatie\ResponseCache;

use Illuminate\Cache\Repository;
use Symfony\Component\HttpFoundation\Response;

class ResponseCacheRepository
{
    /** @var \Illuminate\Cache\Repository */
    protected $cache;

    /** @var \Spatie\ResponseCache\ResponseSerializer */
    protected $responseSerializer;

    public function __construct(ResponseSerializer $responseSerializer, Repository $cache)
    {
        $this->cache = $cache;
        $this->responseSerializer = $responseSerializer;
    }

    /**
     * @param string $key
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \DateTime|int $minutes
     */
    public function put($key, $response, $minutes)
    {
        $this->cache->put($key, $this->responseSerializer->serialize($response), $minutes);
    }

    public function has($key)
    {
        return $this->cache->has($key);
    }

    public function get($key)
    {
        return $this->responseSerializer->unserialize($this->cache->get($key));
    }

    public function flush()
    {
        $this->cache->flush();
    }
}
