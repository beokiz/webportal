<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Traits;

use GuzzleHttp\Client;

/**
 * Trait for making external services requests
 *
 * @package \App\Http\Traits
 */
trait ConsumesExternalServices
{
    /**
     * @param string $method
     * @param string $requestUrl
     * @param array  $queryParams
     * @param array  $formParams
     * @param array  $headers
     * @param bool   $isJsonRequest
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeRequest(string $method, string $requestUrl, array $queryParams = [], array $formParams = [], array $headers = [], bool $isJsonRequest = false)
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        $response = $client->request($method, $requestUrl, [
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers'                               => $headers,
            'query'                                 => $queryParams,
        ]);

        $response = $response->getBody()->getContents();

        if (method_exists($this, 'decodeResponse')) {
            $response = $this->decodeResponse($response);
        }

        return $response;
    }
}
