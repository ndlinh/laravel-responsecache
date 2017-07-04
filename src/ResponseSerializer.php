<?php

namespace Spatie\ResponseCache;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseSerializer
{
    const RESPONSE_TYPE_NORMAL = 'response_type_normal';
    const RESPONSE_TYPE_FILE = 'response_type_file';

    public function serialize(Response $response)
    {
        return serialize($this->getResponseData($response));
    }

    public function unserialize($serializedResponse)
    {
        $responseProperties = unserialize($serializedResponse);

        $response = $this->buildResponse($responseProperties);

        $response->headers = $responseProperties['headers'];

        return $response;
    }

    protected function getResponseData(Response $response)
    {
        $statusCode = $response->getStatusCode();
        $headers = $response->headers;

        if ($response instanceof BinaryFileResponse) {
            $content = $response->getFile()->getPathname();
            $type = self::RESPONSE_TYPE_FILE;

            return compact('statusCode', 'headers', 'content', 'type');
        }

        $content = $response->getContent();
        $type = self::RESPONSE_TYPE_NORMAL;

        return compact('statusCode', 'headers', 'content', 'type');
    }

    protected function buildResponse(array $responseProperties)
    {
        $type = $responseProperties['type'] ? self::RESPONSE_TYPE_NORMAL : '';

        if ($type === self::RESPONSE_TYPE_FILE) {
            return new BinaryFileResponse(
                $responseProperties['content'],
                $responseProperties['statusCode']
            );
        }

        return new Response($responseProperties['content'], $responseProperties['statusCode']);
    }
}
