<?php

namespace App\Services\Shipping\Usps;

use Illuminate\Support\Facades\Http;
use App\Services\Shipping\XmlFormatter;

class Client
{
    protected $host;
    protected $clientId;

    public function __construct(string $host, string $clientId)
    {
        $this->host = $host;
        $this->clientId = $clientId;
        $this->httpClient = Http::retry(3, 30)->timeout(120);
    }

    public function getRates($attrs)
    {
        $rootElement = $this->buildClientAuth("RateV4Request");
        $payload = [
            'API' => 'RATEV4',
            'XML' => XmlFormatter::arrayToXml($attrs, $rootElement),
        ];
        $uri = $this->host . '/ShippingAPI.dll?' . http_build_query($payload);

        $response = $this->httpClient->post($uri);
        return XmlFormatter::xmlToArray($response->body());
    }

    protected function buildClientAuth(string $requestName): array
    {
        return [
            'rootElementName' => $requestName,
            '_attributes' => ['USERID' => $this->clientId]
        ];
    }
}
