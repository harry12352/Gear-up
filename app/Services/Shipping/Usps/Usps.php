<?php

namespace App\Services\Shipping\Usps;

use App\Services\Shipping\Shipment;
use App\Services\Shipping\ShipperInterface;

class Usps implements ShipperInterface
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRate(Shipment $shipment): ?Postage
    {
        $postageRate = $this->client->getRates($shipment->toArray());
        if ($postageRate) {
            $postageRate = $postageRate['Package'];
            $mailService = html_entity_decode($postageRate['Postage']['MailService']);

            return new Postage($mailService, $postageRate['Postage']['Rate']);
        }
    }

    public function createLabel()
    {
        # code...
    }
}
