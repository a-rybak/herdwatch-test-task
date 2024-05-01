<?php

namespace App\Services;

use App\Services\Requests\ApiRequest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchApiService
{
    private string $apiHost;
    private ApiRequest$apiRequest;

    public function __construct(private HttpClientInterface $httpClient, private ParameterBagInterface $params)
    {
        $this->apiHost = $this->params->get('api_url');
    }

    /**
     * @param ApiRequest $apiRequest
     */
    public function setApiRequest(ApiRequest $apiRequest): void
    {
        $this->apiRequest = $apiRequest;
    }

    public function sendRequest()
    {
        $response = $this->httpClient->request(
            $this->apiRequest->getType(),
            $this->makeUrlForRequest(),
            $this->apiRequest->getOptions(),
        );

        return !empty($response) ? json_decode($response->getContent()) : [];
    }

    protected function makeUrlForRequest(): string
    {
        $key = $this->apiRequest->getKey();
        $keyString = isset($key) ? "/".$key : "";
        return $this->apiHost . "/" . $this->apiRequest->getEntity() . $keyString;
    }

}